<?php

use Dotenv\Dotenv;

function get_execution_time(callable $fn): float
{
    $time = -microtime(true);
    $fn();
    return $time + microtime(true);
}

function create_pdo_instance(bool $emulatePrepares): PDO
{
    (Dotenv::createImmutable(__DIR__ . '/../'))->load();

    $host = $_ENV['DB_HOST'];
    $database = $_ENV['DB_NAME'];
    $port = $_ENV['DB_PORT'];
    $user = $_ENV['DB_USERNAME'];
    $password = $_ENV['DB_PASSWORD'];

    $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8;collation=utf8_unicode_ci";

    return new PDO($dsn, $user, $password, [
        PDO::ATTR_EMULATE_PREPARES => $emulatePrepares,
    ]);
}
