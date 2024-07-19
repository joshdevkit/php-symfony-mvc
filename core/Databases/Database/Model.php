<?php

namespace Core\Databases\Database;

use Core\Databases\DB;
use PDO;

abstract class Model
{
    public $id;
    protected $table;
    protected static $primaryKey = 'id';
    protected static $pdo;
    protected $fillable = [];


    public static function init(PDO $pdo)
    {
        self::$pdo = $pdo;
        DB::init($pdo);
    }

    public static function find($id)
    {
        $table = (new static())->getTable();
        $sql = "SELECT * FROM {$table} WHERE " . static::$primaryKey . " = :id";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchObject(static::class);
    }

    public static function all()
    {
        $table = (new static())->getTable();
        $sql = "SELECT * FROM {$table}";
        $stmt = self::$pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    public static function create(array $data)
    {
        $table = (new static())->getTable();
        $keys = array_keys($data);
        $fields = implode(',', $keys);
        $placeholders = ':' . implode(',:', $keys);
        $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})";
        $stmt = self::$pdo->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        $stmt->execute();

        $id = self::$pdo->lastInsertId();
        $data = self::find($id);
        $modelClass = static::class;
        $newModel = new $modelClass();

        foreach ($data as $key => $value) {
            $newModel->$key = $value;
        }

        return $newModel;
    }

    public static function update($id, array $data)
    {
        $table = (new static())->getTable();
        $fillableFields = array_intersect_key($data, array_flip((new static())->fillable));

        $fields = '';
        foreach ($fillableFields as $key => $value) {
            $fields .= "$key = :$key,";
        }
        $fields = rtrim($fields, ',');

        $sql = "UPDATE {$table} SET {$fields} WHERE " . static::$primaryKey . " = :id";
        $data['id'] = $id;
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($fillableFields + ['id' => $id]);

        return self::find($id);
    }


    public static function delete($id)
    {
        $table = (new static())->getTable();
        $sql = "DELETE FROM {$table} WHERE " . static::$primaryKey . " = :id  ";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt;
    }

    public static function where($column, $operator, $value)
    {
        $table = (new static())->getTable();
        $validOperators = ['=', '!=', '<', '>', '<=', '>='];

        if (!in_array($operator, $validOperators)) {
            throw new \InvalidArgumentException("Invalid operator: {$operator}");
        }

        $sql = "SELECT * FROM {$table} WHERE {$column} {$operator} :value";
        $stmt = self::$pdo->prepare($sql);
        $stmt->bindValue(':value', $value);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    // public static function where($column, $operator, $value)
    // {
    //     $table = (new static())->getTable();
    //     $validOperators = ['=', '!=', '<', '>', '<=', '>=']; // Define valid operators

    //     if (!in_array($operator, $validOperators)) {
    //         throw new \InvalidArgumentException("Invalid operator: {$operator}");
    //     }

    //     $sql = "SELECT * FROM {$table} WHERE {$column} {$operator} :value";
    //     $stmt = self::$pdo->prepare($sql);
    //     $stmt->bindValue(':value', $value);
    //     $stmt->execute();

    //     return $stmt->fetchAll(PDO::FETCH_CLASS, static::class);
    // }


    public static function first(array $conditions = [])
    {
        $table = (new static())->getTable();

        $sql = "SELECT * FROM {$table}";

        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $firstCondition = true;
            foreach ($conditions as $column => $value) {
                if (!$firstCondition) {
                    $sql .= " AND ";
                }
                $sql .= "{$column} = :{$column}";
                $firstCondition = false;
            }
        }

        $sql .= " LIMIT 1";

        $stmt = self::$pdo->prepare($sql);
        $stmt->execute($conditions);
        return $stmt->fetchObject(static::class);
    }

    public static function findOne($column, $value)
    {
        $instance = new static();
        return $instance->getOne($column, $value);
    }

    protected function getOne($column, $value)
    {
        $sql = "SELECT * FROM {$this->getTable()} WHERE {$column} = :value";
        $stmt = self::$pdo->prepare($sql);
        $stmt->bindValue(':value', $value);
        $stmt->execute();
        return $stmt->fetchObject(static::class);
    }


    public function hasMany($relatedModel, $foreignKey)
    {
        $table = (new $relatedModel())->getTable();

        $primaryKey = static::$primaryKey;

        $sql = "SELECT * FROM {$table} WHERE {$foreignKey} = :id";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['id' => $this->$primaryKey]);

        return $stmt->fetchAll(PDO::FETCH_CLASS, $relatedModel);
    }

    public function belongsTo($relatedModel, $foreignKey)
    {
        $table = (new $relatedModel())->getTable();

        $primaryKey = static::$primaryKey;

        $sql = "SELECT * FROM {$table} WHERE {$primaryKey} = :id";
        $stmt = self::$pdo->prepare($sql);
        $stmt->execute(['id' => $this->$foreignKey]);

        return $stmt->fetchObject($relatedModel);
    }


    public function save()
    {
        $fields = get_object_vars($this);

        if (isset($fields[static::$primaryKey])) {
            $primaryKeyValue = $fields[static::$primaryKey];
            $fillableFields = array_intersect_key($fields, array_flip($this->fillable));

            $fieldsToUpdate = '';
            foreach ($fillableFields as $key => $value) {
                if ($key !== static::$primaryKey) {
                    $fieldsToUpdate .= "$key = :$key,";
                }
            }
            $fieldsToUpdate = rtrim($fieldsToUpdate, ',');

            $sql = "UPDATE {$this->table} SET {$fieldsToUpdate} WHERE " . static::$primaryKey . " = :id";
            $stmt = self::$pdo->prepare($sql);
            $stmt->execute($fillableFields + ['id' => $primaryKeyValue]);
        }
    }

    public function getTable()
    {
        return $this->table;
    }
}
