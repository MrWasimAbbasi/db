<?php

namespace DB;

use PDO;
use PDOStatement;

class QueryResult
{
    private PDOStatement $statement;

    public function __construct(PDOStatement $statement)
    {
        $this->statement = $statement;
    }

    public function one(): ?array
    {
        return $this->statement->fetch(PDO::FETCH_ASSOC);
    }

    public function all(): array
    {
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
