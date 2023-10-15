<?php

namespace easyphp\core\easyorm;

use easyphp\core\db\Database;
use Easyphp\Core\Logs\ErrorLog;
use PDO;
use PDOException;

class EasyOrm extends Database
{

    protected $table;
    private $stmt;
    private $sql;


    public function select($column = ['*'])
    {
        try {
            $column = implode(',', $column);

            $this->sql = "SELECT $column FROM $this->table;";
            $this->stmt = self::getConnection()->prepare($this->sql);
            return $this;
        } catch (PDOException $e) {
            ErrorLog::notify(date("Y-m-d H:i:s") . " Error in select" . $this->table . ": " . $e->getMessage());
        }
    }

    public function insert($data)
    {
        try {
            $columns = array_keys($data);
            $values = array_values($data);
            $columnNames = implode(', ', $columns);
            $placeholders = implode(', ', array_map(function ($column) {
                return ':' . $column;
            }, $columns));

            $this->sql = "INSERT INTO $this->table ($columnNames) VALUES ($placeholders)";
            $statement = self::getConnection()->prepare($this->sql);

            foreach ($columns as $index => $column) {
                $statement->bindValue(':' . $column, $values[$index]);
            }

            $statement->execute();
            return $statement->rowCount() > 0;
        } catch (PDOException $e) {
            ErrorLog::notify(date("Y-m-d H:i:s") . " Error in insert" . $this->table . ": " . $e->getMessage());
        }
    }
    public function update($column, $id, $data)
    {
        try {
            $columns = array_keys($data);
            $values = array_values($data);
            $placeholders = array_map(function ($column) {
                return $column . ' = :' . $column;
            }, $columns);
            $placeholders = implode(', ', $placeholders);

            $this->sql = "UPDATE $this->table SET $placeholders WHERE $column = :id";
            $statement = self::getConnection()->prepare($this->sql);
            $statement->bindValue(':id', $id);

            foreach ($columns as $index => $column) {
                $statement->bindValue(':' . $column, $values[$index]);
            }

            $statement->execute();
            return $statement->rowCount() > 0;
        } catch (PDOException $e) {
            ErrorLog::notify(date("Y-m-d H:i:s") . " Error in update" . $this->table . ": " . $e->getMessage());
        }
    }

    public function delete($column, $id)
    {
        $id = intval($id);
        try {
            $this->sql = "DELETE FROM $this->table WHERE $column = :id";
            $statement = self::getConnection()->prepare($this->sql);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->execute();
            return $statement->rowCount() > 0;
        } catch (PDOException $e) {
            ErrorLog::notify(date("Y-m-d H:i:s") . " Error in delete" . $this->table . ": " . $e->getMessage());
        }
    }

    public function where($column, $id)
    {
        $id = intval($id);
        try {
            $this->sql .= " WHERE $this->table.$column = :id";
            $this->stmt = self::getConnection()->prepare($this->sql);
            $this->stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $this;
        } catch (PDOException $e) {
            ErrorLog::notify(date("Y-m-d H:i:s") . " Error in where" . $this->table . ": " . $e->getMessage());
        }
    }

    public function join($table, $column)
    {
        try {
            $this->sql .= " INNER JOIN $table ON $this->table.$column = $table.$column";
            return $this;
        } catch (PDOException $e) {
            ErrorLog::notify(date("Y-m-d H:i:s") . " Error in join" . $this->table . ": " . $e->getMessage());
        }
    }

    public function all()
    {
        try {
            $this->stmt->execute();
            return $this->stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            ErrorLog::notify(date("Y-m-d H:i:s") . " Error in all" . $this->table . ": " . $e->getMessage());
        }
    }

    public function one()
    {
        try {
            $this->stmt->execute();
            return $this->stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            ErrorLog::notify(date("Y-m-d H:i:s") . " Error in one" . $this->table . ": " . $e->getMessage());
        }
    }

    public function lastInsertId()
    {
        return self::getConnection()->lastInsertId();
    }
}
