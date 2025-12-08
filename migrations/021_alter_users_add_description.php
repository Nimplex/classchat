<?php

/** @var PDO $db */
require __DIR__ . '/../bootstrap.php';

$sql = <<<SQL
ALTER TABLE users
ADD COLUMN description VARCHAR(1000);
SQL;

$db->exec($sql);

echo "Migration 021 applied\n";
