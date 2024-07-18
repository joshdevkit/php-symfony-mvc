<?php

namespace Core\Databases;

use PDO;

class DB
{
    protected static $pdo;

    public static function init(PDO $pdo)
    {
        self::$pdo = $pdo;
    }

    public static function table($table)
    {
        return new QueryBuilder($table);
    }

    public static function executeQuery($sql, $params = [])
    {
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}

class QueryBuilder
{
    protected $table;
    protected $joins = [];

    public function __construct($table)
    {
        $this->table = $table;
    }

    public function join($table, $first, $operator, $second, $type = 'INNER')
    {
        $this->joins[] = "{$type} JOIN {$table} ON {$first} {$operator} {$second}";
        return $this;
    }

    public function select($columns = ['*'])
    {
        $columns = implode(', ', $columns);
        $sql = "SELECT {$columns} FROM {$this->table}";

        if (!empty($this->joins)) {
            $sql .= ' ' . implode(' ', $this->joins);
        }

        return DB::executeQuery($sql);
    }
}
