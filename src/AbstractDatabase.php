<?php
/*
 * This file is part of the deloachtech/database package.
 *
 * Copyright (c) DeLoach Tech
 * https://deloachtech.com
 *
 * This source code is protected under international copyright law. All
 * rights reserved and protected by the copyright holders. This file is
 * confidential and only available to authorized individuals with the
 * permission of the copyright holder. If you encounter this file, and do
 * not have permission, please contact the copyright holder and delete
 * this file. Unauthorized copying of this file, via any medium is strictly
 * prohibited.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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


    public function gerError(): array
    {
        return ['code' => $this->pdo->errorCode(), 'info' => $this->pdo->errorInfo()];
    }


    /**
     * Prepares the sql string with the variables provided.
     * Returns an associative array for SELECT queries and
     * a boolean for INSERT/UPDATE queries.
     * @param string $sql
     * @param array $variables
     * @return bool|array
     */
    public function query(string $sql, array $variables = [])
    {
        $select = strstr(strtolower($sql), 'select') != false;

        $stmt = $this->pdo->prepare($sql);
        if ($stmt->execute($variables)) {
            if($select){
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return is_array($result) ? $result : [];
            }else{
                return true;
            }
        }
        return $select ? [] : false;
    }


    /**
     * Returns an associative array of values (or empty array).
     * @param string $sql
     * @param array $variables
     * @return array
     */
//    public function querySelect(string $sql, array $variables = []): array
//    {
//        $stmt = $this->pdo->prepare($sql);
//        if ($stmt->execute($variables)) {
//            $result = $stmt->fetch(PDO::FETCH_ASSOC);
//            if ($result !== false) {
//                return $result;
//            }
//        }
//        return [];
//    }

    /**
     * Prepares the sql string with the variables provided.
     * Returns an array or false on execution failure.
     * @param string $sql
     * @param array $variables
     * @return array|false
     */
    public function queryList(string $sql, array $variables = [])
    {
        $stmt = $this->pdo->prepare($sql);
        if($stmt->execute($variables)){
            return $stmt->fetchAll(PDO::FETCH_ASSOC)??[];
        }
        return false;
    }
}