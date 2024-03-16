<?php

namespace DB\Tests;

use DB\DB;
use PHPUnit\Framework\TestCase;

class DBTest extends TestCase
{
    private DB $db;

    protected function setUp(): void
    {
        $this->db = new DB('localhost', 'username', 'password', 'test_database');
    }

    protected function tearDown(): void
    {
        $this->db->close();
    }

    public function testQuery(): void
    {
        $result = $this->db->query("SELECT * FROM users");
        $this->assertTrue($result instanceof \DB\QueryResult);
    }

    public function testOne(): void
    {
        $result = $this->db->query("SELECT * FROM users WHERE id = ?", [1]);
        $row = $result->one();
        $this->assertIsArray($row);
        $this->assertArrayHasKey('id', $row);
    }

    public function testAll(): void
    {
        $result = $this->db->query("SELECT * FROM users");
        $rows = $result->all();
        $this->assertIsArray($rows);
        $this->assertNotEmpty($rows);
    }

    // Add more test cases as needed
}
