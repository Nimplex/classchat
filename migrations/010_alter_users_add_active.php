<?php

/** @var PDO $db */
require __DIR__ . '/../bootstrap.php';

$sql = <<<SQL
ALTER TABLE users ADD active BOOLEAN NOT NULL DEFAULT FALSE;
SQL;

$db->exec($sql);

echo "Migration 010 applied\n";
