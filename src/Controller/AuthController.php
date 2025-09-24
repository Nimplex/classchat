<?php

namespace App\Controller;

use App\Service\AuthService;

class AuthController
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(array $request): string
    {
        $login = $request['login'] ?? null;
        $email = $request['email'] ?? null;
        $password = $request['password'] ?? null;

        if (!$login || !$email || !$password) {
            return "Missing fields";
        }

        try {
            $this->authService->register($login, $email, $password);
            return "Registration successful";
        } catch (\Exception $e) {
            return "Error: {$e->getMessage()}";
        }
    }

    public function login(array $request): string
    {
        $email = $request['email'] ?? null;
        $password = $request['password'] ?? null;

        try {
            $res = $this->authService->login($email, $password);

            if ($res == -1) {
                return "User doesn't exist or the password is invalid";
            }

            $_SESSION['user_id'] = $res;

            return "Logged in successfully";
        } catch (\Exception $e) {
            return "Error: {$e->getMessage()}";
        }
    }
}
