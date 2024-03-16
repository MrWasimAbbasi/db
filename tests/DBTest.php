<?php

use DB\DB;
use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

class DBTest extends TestCase
{
    private DB $db;

    protected function setUp(): void
    {
        // Load environment variables from .env file
        $dotenv = Dotenv::createImmutable(__DIR__ . '/..'); // Adjust the path as needed
        $dotenv->load();

        $this->pdo = new \PDO("mysql:host=". $_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);

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
        $this->db = new DB($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_DATABASE']);


    }

    protected function tearDown(): void
    {
        // Close DB connection after each test
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

}
