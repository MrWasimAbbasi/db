<?php

namespace DB;

use PDO;
use PDOException;

class DB
{
    private ?PDO $connection = null;

    public function __construct(
        private string $host,
        private string $username,
        private string $password,
        private string $database,
        private array $options = []
    ) {
        $this->connect();
    }

    private function connect(): void
    {
        $dsn = "mysql:host={$this->host};dbname={$this->database};charset=utf8mb4";

        try {
            $this->connection = new PDO($dsn, $this->username, $this->password, $this->options);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function query(string $sql, array $params = []): QueryResult
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return new QueryResult($stmt);
        } catch (PDOException $e) {
            die("Query execution failed: " . $e->getMessage());
        }
    }

    public function close(): void
    {
        $this->connection = null;
    }
}
