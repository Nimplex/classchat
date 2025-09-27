<?php

namespace App\Model;

use PDO;

class BaseDBModel
{
    private PDO $db;

    protected function __construct(PDO $db)
    {
        $this->db = $db;
    }
}
