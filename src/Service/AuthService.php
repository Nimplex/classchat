<?php

namespace App\Service;

use App\Model\User;

class AuthService
{
    private User $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function register(string $login, string $email, string $password): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email format");
        }
        
        if (strlen($password) < 8) {
            throw new \InvalidArgumentException("Password has to be at least 8 characters long");
        }
        
        return $this->userModel->create($login, $email, $password);
    }

    public function login(string $email, string $password): int
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Invalid email format");
        }
        
        $user = $this->userModel->findByEmail($email);
        
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user['id'];
        }
        
        return -1;
    }
}
