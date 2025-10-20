<?php

/** @var PDO $db */
require __DIR__ . '/../bootstrap.php';

$sql = <<<SQL
ALTER TABLE users ADD display_name VARCHAR(255); 
SQL;

$db->exec($sql);

echo "Migration 007 applied\n";
