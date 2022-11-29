<?php

namespace DeLoachTech\Database;

use PDO;

abstract class AbstractDatabase
{
    private $pdo;


    public function __construct(string $host, string $user, string $password, string $database, string $charset = 'utf8mb4')
    {
        $dsn = "mysql:host={$host};dbname={$database};charset={$charset}";
        try {
            $this->pdo = new PDO($dsn, $user, $password);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }


    public function inValues(array $stringIds): string
    {
        return "'" . implode("','", $stringIds) . "'";
    }


    public function beginTransaction()
    {
        $this->pdo->beginTransaction();
    }


    public function rollback()
    {
        $this->pdo->rollBack();
    }


    public function commit()
    {
        $this->pdo->commit();
    }


    public function query(string $sql, array $variables = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($variables);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function queryList(string $sql, array $variables = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($variables);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}