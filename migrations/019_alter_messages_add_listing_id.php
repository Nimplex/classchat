<?php

/** @var PDO $db */
require __DIR__ . '/../bootstrap.php';

$sql = <<<SQL
ALTER TABLE chats ADD COLUMN listing_id INT REFERENCES listings(id);
SQL;

$db->exec($sql);

echo "Migration 019 applied\n";
