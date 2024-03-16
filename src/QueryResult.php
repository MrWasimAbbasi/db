<?php

namespace DB;
class QueryResult
{
    private \mysqli_result $result;

    public function __construct(\mysqli_result $result)
    {
        $this->result = $result;
    }

    public function one(): ?array
    {
        return $this->result->fetch_assoc();
    }

    public function all(): array
    {
        $rows = [];
        while ($row = $this->result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }
}
