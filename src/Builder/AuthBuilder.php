<?php

namespace App\Builder;

use App\Controller\UserController;

class AuthBuilder
{
    public function make(): UserController 
    {
        require __DIR__ . '/../../bootstrap.php';

        /** @var PDO $db */
        $user = new UserController($db);
        return $user;
    }
}
