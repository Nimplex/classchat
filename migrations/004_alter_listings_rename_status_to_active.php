<?php

/** @var PDO $db */
require __DIR__ . '/../bootstrap.php';

$sql = <<<SQL
ALTER TABLE listings
RENAME COLUMN status TO active;
SQL;

$db->exec($sql);

echo "Migration 004 applied\n";
