<?php

global $DB, $DB_CONFIG;

require_once __DIR__ . '/config/schema.php';

$driver = $DB_CONFIG['driver'] ?? 'sqlite';

ecommerceCreateTables($DB, $driver);
ecommerceRunMigrations($DB, $driver);
