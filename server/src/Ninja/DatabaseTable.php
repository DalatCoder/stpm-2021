<?php

namespace Ninja;

class DatabaseTable
{
    private \PDO $pdo;
    private string $table;
    private string $primaryKey;
    private $className;
    private $constructorArgs;

    public function __construct(
        string $table,
        string $primaryKey,
        string $className = '\stdClass',
        array $constructorArgs = []
    ) {

        $this->init_database();

        $this->table = $table;
        $this->primaryKey = $primaryKey;
        $this->className = $className;
        $this->constructorArgs = $constructorArgs;
    }

    public function init_database()
    {
        $db_name = NJConfiguration::get('db_name') ?? null;
        $db_host = NJConfiguration::get('db_host') ?? null;
        $db_charset = NJConfiguration::get('db_charset') ?? null;
        $db_username = NJConfiguration::get('db_username') ?? null;
        $db_password = NJConfiguration::get('db_password') ?? null;
        
        $this->pdo = new \PDO("mysql:host=$db_host;dbname=$db_name;charset=$db_charset", $db_username, $db_password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function findAll($orderBy = null, $orderDirection = null, $limit = null, $offset = null)
    {
        $sql = "SELECT * FROM `{$this->table}`";

        if ($orderBy != null && $orderDirection != null) {
            $sql .= " ORDER BY `{$orderBy}` {$orderDirection}";
        }

        if ($limit != null) {
            $sql .= " LIMIT {$limit}";
        }

        if ($offset != null) {
            $sql .= " OFFSET {$offset}";
        }

        $result = $this->query($sql);

        return $result->fetchAll(\PDO::FETCH_CLASS, $this->className, $this->constructorArgs);
    }

    public function find($column, $value, $orderBy = null, $orderDirection = null, $limit = null, $offset = null)
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE `$column` = :value";

        if ($orderBy != null && $orderDirection != null) {
            $sql .= " ORDER BY `{$orderBy}` {$orderDirection}";
        }

        if ($limit != null) {
            $sql .= " LIMIT {$limit}";
        }

        if ($offset != null) {
            $sql .= " OFFSET {$offset}";
        }

        $parameters = [
            'value' => $value
        ];
        $parameters = $this->processDate($parameters);

        $query = $this->query($sql, $parameters);

        return $query->fetchAll(\PDO::FETCH_CLASS, $this->className, $this->constructorArgs);
    }


    public function findById($value)
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = :value";

        $parameters = [
            'value' => $value
        ];

        $query = $this->query($sql, $parameters);

        return $query->fetchObject($this->className, $this->constructorArgs);
    }

    public function total()
    {
        $sql = "SELECT COUNT(*) FROM `{$this->table}`";
        $query = $this->query($sql);
        $row = $query->fetch();
        return $row[0];
    }

    private function insert($fields)
    {
        $sql = 'INSERT INTO `' . $this->table . '` (';

        foreach ($fields as $key => $value) {
            $sql .= "`$key`, ";
        }

        $sql = rtrim($sql, ', ');

        $sql .= ') VALUES (';

        foreach ($fields as $key => $value) {
            $sql .= ":$key, ";
        }

        $sql = rtrim($sql, ', ');

        $sql .= ')';

        $fields = $this->processDate($fields);

        $this->query($sql, $fields);

        return $this->pdo->lastInsertId();
    }

    private function update($fields)
    {
        $sql = 'UPDATE `' . $this->table . '` SET ';

        foreach ($fields as $field_name => $updated_value) {
            $sql .= "`$field_name` = :$field_name, ";
        }
        $sql = rtrim($sql, ', ');

        $sql .= ' WHERE `' . $this->primaryKey . '` = :primaryKey';

        $fields = $this->processDate($fields);

        $fields['primaryKey'] = $fields['id'];

        $this->query($sql, $fields);
    }

    public function save($record)
    {
        $entity = new $this->className(...$this->constructorArgs);

        try {
            if (!array_key_exists($this->primaryKey, $record)) {
                $record[$this->primaryKey] = null;
            }

            if ($record[$this->primaryKey] == '') {
                $record[$this->primaryKey] = null;
            }

            $insertId = $this->insert($record);

            $primaryKeyColumn = $this->primaryKey;
            $entity->$primaryKeyColumn = $insertId;
        } catch (\PDOException $e) {
            $this->update($record);
        }

        foreach ($record as $key => $value) {
            if (!empty($value)) {
                $entity->$key = $value;
            }
        }

        return $entity;
    }

    public function delete($id)
    {
        $sql = "DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id";
        $parameters = [
            'id' => $id
        ];

        $this->query($sql, $parameters);
    }

    public function deleteWhere($column, $value)
    {
        $query = "DELETE FROM `{$this->table}` WHERE `$column` = :value";

        $parameters = [
            'value' => $value
        ];

        $query = $this->query($query, $parameters);
    }

    private function query($sql, $parameters = [])
    {
        $query = $this->pdo->prepare($sql);

        foreach ($parameters as $name => $value) {
            $query->bindValue($name, $value);
        }

        $query->execute();
        // $query->execute($parameters);
        return $query;
    }

    private function processDate($fields)
    {
        foreach ($fields as $key => $value) {
            if ($value instanceof \DateTime) {
                $fields[$key] = $value->format('Y-m-d');
            }
        }

        return $fields;
    }
}
