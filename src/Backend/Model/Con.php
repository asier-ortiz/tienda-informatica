<?php

namespace Backend\Model;

use Error;
use PDO;
use PDOException;
use PDOStatement;

class Con
{
    private static $instance;
    private PDO $conn;

    public function __construct()
    {
        $opt = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_BOTH,
            PDO::ATTR_EMULATE_PREPARES => FALSE,
        );

        $dsn = 'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'] . ';charset=' . $_ENV['DB_CHAR'];
        $this->conn = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $opt);
    }

    public static function getInstance(): ?Con
    {
        if (self::$instance === null) self::$instance = new self;
        return self::$instance;
    }

    public function run($sql, $args = []): ?PDOStatement
    {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($args);
            return $stmt;
        } catch (Error $err) {
            echo $err->getMessage();
            return null;
        } catch (PDOException $p) {
            echo $p->getMessage();
            return null;
        }
    }
}