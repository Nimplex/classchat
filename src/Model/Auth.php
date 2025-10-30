<?php

namespace App\Model;

require $_SERVER['DOCUMENT_ROOT'] . '/../vendor/autoload.php';
use App\Mailer;
use PDO;

class Auth extends BaseDBModel
{
    private const MIN_PASS_LENGTH = 8;

    // (WIP)
    // TODO: Implement resending activation codes.
    // TODO: check for existing codes in database, if so then resend. If not
    // generate new and send to user. Implement rate limiting.
    
    private function _getActivation(int $user_id, string $token): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM activation WHERE user_id = ? AND code_hash = ?');
        $stmt->execute([$user_id, $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private function _generateActivation(int $user_id): string
    {
        $stmt = $this->db->prepare(<<<SQL
        INSERT INTO activation (user_id, code_hash, expires_at)
        VALUES (:uid, :token, NOW() + INTERVAL '10 minutes')
        SQL);

        $token = bin2hex(random_bytes(32));
        $hash = hash('sha256', $token);
        $stmt->bindValue(':uid', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':token', $hash, PDO::PARAM_STR);

        $stmt->execute();
        return $hash;
    }

    private function _activate(int $user_id, string $token): bool
    {
        $stmt = $this->db->prepare(<<<SQL
        UPDATE users SET active = true WHERE id = ?
        SQL);
        $stmt->execute([$user_id]);

        $stmt = $this->db->prepare(<<<SQL
        DELETE FROM activation WHERE user_id = ? AND code_hash = ?
        SQL);
        $stmt->execute([$user_id, $token]);

        // TODO: implement error handling, transaction or whatnot
        return true;
    }

    private function _findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private function _login_repeats(string $login): bool
    {
        $stmt = $this->db->prepare('SELECT 1 FROM users WHERE login = ?');
        $stmt->execute([$login]);
        return !empty($stmt->fetch(PDO::FETCH_ASSOC) ?: null);
    }

    private function _email_repeats(string $email): bool
    {
        $stmt = $this->db->prepare('SELECT 1 FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return !empty($stmt->fetch(PDO::FETCH_ASSOC) ?: null);
    }

    private function _create(string $login, string $display_name, string $email, string $password): int|false
    {
        if ($this->_login_repeats($login)) {
            throw new \InvalidArgumentException('Ten login już jest w użyciu', 1);
        }

        if ($this->_email_repeats($email)) {
            throw new \InvalidArgumentException('Ten adres e-mail już jest w użyciu', 1);
        }
        
        $stmt = $this->db->prepare('INSERT INTO users (login, display_name, email, password_hash) VALUES (:login, :display_name, :email, :password_hash)');

        // https://www.php.net/manual/en/function.password-hash.php
        // I've decided that ARGON2ID is stronger than bcrypt
        $hash = password_hash($password, PASSWORD_ARGON2ID, [
            'memory_cost' => 1 << 16,
            'time_cost' => 4,
            'threads' => 2,
        ]);

        $res = $stmt->execute([
            ':login' => $login,
            ':display_name' => $display_name,
            ':email' => $email,
            ':password_hash' => $hash,
        ]);

        if ($res) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    // --- public methods ---

    /**
     * @throws \InvalidArgumentException
     */
    public function register(string $login, string $display_name, string $email, string $password): int | false
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Nieprawidłowy adres e-mail', 1);
        }
        
        if (strlen($password) < Auth::MIN_PASS_LENGTH) {
            throw new \InvalidArgumentException("Hasło musi składać się z conajmniej ${Auth::MIN_PASS_LENGTH} znaków", 1);
        }

        return $this->_create($login, $display_name, $email, $password);
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function login(string $email, string $password): array
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Nieprawidłowy adres e-mail', 1);
        }

        $user = $this->_findByEmail($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            if (!$user['active']) {
                throw new \InvalidArgumentException('Konto nie zostało aktywowane, sprawdź pocztę e-mail', 1);
            }

            return [
                'login' => $user['login'],
                'email' => $user['email'],
                'display_name' => $user['display_name'],
                'id' => $user['id'],
            ];
        }

        throw new \InvalidArgumentException('Nieprawidłowy e-mail lub hasło', 1);
    }

    /**
     * @param array<string,mixed> $request
     */
    public function activate_from_request(array $request): bool
    {
        $id = $request['id'];
        $token = $request['token'];

        if (!isset($id) || !isset($token)) {
            throw new \InvalidArgumentException('Missing params');
        }

        $activation = $this->_getActivation($id, $token);

        if (!$activation) {
            throw new \InvalidArgumentException('Invalid activation url');
        }

        return $this->_activate($id, $token);
    }

    /**
     * @param array<int,string|null> $request
     * @throws \InvalidArgumentException
     */
    public function register_from_request(array $request): bool
    {
        $login = $request['login'] ?? null;
        $display_name = $request['display_name'] ?? null;
        $email = $request['email'] ?? null;
        $password = $request['password'] ?? null;

        if (!$login || !$display_name || !$email || !$password) {
            throw new \InvalidArgumentException('Missing fields');
        }

        $res = $this->register($login, $display_name, $email, $password);

        if ($res) {
            $token = $this->_generateActivation($res);

            $host = $_SERVER['SERVER_NAME'];
            $protocol = ($host == 'localhost' || $host == '127.0.0.1') ? 'http' : 'https';

            $mailer = new Mailer();
            $mailer->send(
                $email,
                'Potwierdzenie rejestracja',
                'activation',
                ['name' => $display_name, 'link' => "$protocol://$host/api/activate/$res/$token"]
            );
        }

        return $res;
    }

    /**
     * @param array<int,string|null> $request
     * @throws \InvalidArgumentException
     */
    public function login_from_request(array $request): void
    {
        $email = $request['email'] ?? null;
        $password = $request['password'] ?? null;

        $res = $this->login($email, $password);

        $_SESSION['user_id'] = $res['id'];
        $_SESSION['user_email'] = $res['email'];
        $_SESSION['user_display_name'] = $res['display_name'];
        $_SESSION['user_login'] = $res['login'];
    }
}
