<?php

namespace DB;

class DB
{
    private \mysqli $connection;

    public function __construct(
        private string $host,
        private string $username,
        private string $password,
        private string $database
    ) {
        $this->connection = new \mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function query(string $sql): QueryResult
    {
        $result = $this->connection->query($sql);
        if ($result === false) {
            die("Query execution failed: " . $this->connection->error);
        }
        return new QueryResult($result);
    }

    public function close(): void
    {
        $this->connection->close();
    }
}
