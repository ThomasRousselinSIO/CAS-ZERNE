<?php

declare(strict_types=1);

final class Database
{
    private PDO $connection;

    public function __construct()
    {
        if (!in_array('mysql', PDO::getAvailableDrivers(), true)) {
            throw new RuntimeException(
                'Le pilote PDO MySQL est requis. Activez pdo_mysql dans PHP et démarrez MySQL.'
            );
        }

        $host = getenv('ZERNE_DB_HOST') ?: '127.0.0.1';
        $port = getenv('ZERNE_DB_PORT') ?: '3306';
        $database = getenv('ZERNE_DB_NAME') ?: 'zerne';
        $user = getenv('ZERNE_DB_USER') ?: 'root';
        $password = getenv('ZERNE_DB_PASSWORD') ?: '';
        $serverConnection = new PDO(
            "mysql:host=$host;port=$port;charset=utf8mb4",
            $user,
            $password,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        $serverConnection->exec(
            'CREATE DATABASE IF NOT EXISTS `' . str_replace('`', '``', $database) . '` '
            . 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci'
        );

        $this->connection = new PDO(
            "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4",
            $user,
            $password
        );
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $schema = file_get_contents(__DIR__ . '/../BDD/schema.sql');
        if ($schema === false) {
            throw new RuntimeException('Le schéma de base de données est introuvable.');
        }
        $this->connection->exec($schema);
    }

    public function connection(): PDO
    {
        return $this->connection;
    }
}
