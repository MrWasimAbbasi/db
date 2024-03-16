<?php

namespace DB\Tests;

use DB\DB;
use PDO;
use PHPUnit\Framework\TestCase;

class DBTest extends TestCase
{
    private DB $db;
    private PDO $pdo;

    protected function setUp(): void
    {
        // Connect to MySQL server (replace with your actual credentials)
        $this->pdo = new PDO("mysql:host=localhost", "root", "");

        // Create database
        $this->pdo->exec("CREATE DATABASE IF NOT EXISTS test_database");
        $this->pdo->exec("USE test_database");

        // Create users table
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL,
            email VARCHAR(100) NOT NULL
        )");

        // Insert some data
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
        $stmt->execute(["name" => "John Doe", "email" => "john@example.com"]);
        $stmt->execute(["name" => "Jane Doe", "email" => "jane@example.com"]);

        // Instantiate DB object
        $this->db = new DB('localhost', 'root', '', 'test_database');
    }

    protected function tearDown(): void
    {
        // Drop the test database after each test
        $this->pdo->exec("DROP DATABASE IF EXISTS test_database");
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
        $this->assertCount(2, $rows); // Assuming 2 rows were inserted
    }

    // Add more test cases as needed
}
