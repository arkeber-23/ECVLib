<?php

namespace Kernel\Core\EcvOrm;

use Kernel\Core\DataBase\Connection;
use Kernel\Core\Logger\Errors;
use PDO;
use PDOException;

class TitanOrm extends Connection
{

    protected $table;
    protected $data = [];
    private $stmt;
    private $sql;

    /**
     * Selects data from the database table.
     *
     * @param array $column The columns to select. Default is ['*'].
     * @throws PDOException If there is an error executing the SQL query.
     * @return $this The current instance of the class.
     */
    public function select($column = ['*'])
    {
        $column = is_array($column) ?  implode(',', $column) : $column;
        $this->sql = "SELECT $column FROM $this->table";
        return $this;
    }

    /**
     * Inserts data into the table.
     *
     * @param mixed $data The data to be inserted.
     * @throws PDOException If an error occurs during the insertion process.
     * @return bool Returns true if the insertion is successful, false otherwise.
     */
    public function insert($data = [])
    {
        $this->data = $data;
        return $this->executeInsert();
    }


    public function save()
    {
        if (is_null($this->data)) {
            throw new PDOException('data cannot be null');
        }

        return $this->executeInsert();
    }

    /**
     * Updates a record in the database table.
     *
     * @param string $column The column to search for the record.
     * @param mixed $id The ID of the record to update.
     * @param array $data An associative array containing the updated data.
     * @throws PDOException If there is an error executing the SQL statement.
     * @return bool Returns true if the record was successfully updated, false otherwise.
     */
    public function update($data = [], $column = 'id', $id = null)
    {
        try {

            if (is_null($id)) {
                throw new PDOException('id cannot be null');
            }
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
            $this->setError('update', $e->getMessage());
        }
    }

    /**
     * Deletes a record from the database table based on the given column and ID.
     *
     * @param string $column The column name to match against.
     * @param int $id The ID of the record to delete.
     * @throws PDOException If an error occurs while executing the delete query.
     * @return bool Returns true if the record was successfully deleted, false otherwise.
     */
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
            $this->setError('delete', $e->getMessage());
        }
    }

    /**
     * Sets the WHERE clause for the SQL query.
     *
     * @param string $column The column name.
     * @param mixed $value The value to compare against.
     * @param string $operator The comparison operator. Defaults to '='.
     * @return $this The current instance of the class.
     */
    public function where($column, $value, $operator = '=')
    {
        $validOperators = ['=', '>', '<', '>=', '<=', '!=', '<>', 'LIKE', 'BETWEEN', 'IN'];

        $operator = in_array($operator, $validOperators) ? $operator : '=';

        $whereClause = (strpos($this->sql, 'WHERE') === false) ? ' WHERE ' : ' AND ';

        if ($operator == 'IN') {
            $value = '(' . implode(', ', $value) . ')';
        } elseif ($operator == 'BETWEEN') {
            $value = $value[0] . ' AND ' . $value[1];
        } else {
            $value = "'$value'";
        }

        $this->sql .= $whereClause . "$column $operator $value";

        return $this;
    }


    /**
     * Join a table with the current query.
     *
     * @param string $table The name of the table to join.
     * @param string $firstColumn The column on which to perform the join.
     * @param string $operator The operator to use for the join.
     * @param string|null $secondColumn The column to join on, or null if using only the first column.
     * @param string $type The type of join to perform. Must be one of: inner, left, right, full, cross.
     * @param string|bool $where The WHERE condition for the join, or false to exclude.
     * @throws \InvalidArgumentException If an invalid join type is provided.
     * @return $this Returns the current instance of the class for method chaining.
     */
    public function join($table, $firstColumn, $operator = '=', $secondColumn = null, $type = 'inner', $where = false)
    {
        $allowedTypes = ['inner', 'left', 'right', 'full', 'cross'];

        if (!in_array($type, $allowedTypes)) {
            throw new \InvalidArgumentException("Invalid join type: $type");
        }

        $joinSql = " $type JOIN $table";

        if ($secondColumn === null) {
            $joinSql .= " ON $firstColumn";
        } else {
            $joinSql .= " ON $this->table.$firstColumn $operator $table.$secondColumn";
        }

        if ($where) {
            $joinSql .= " WHERE $where";
        }

        $this->sql .= $joinSql;

        return $this;
    }


    /**
     * Set the column to order the result set by.
     *
     * @param string $column The name of the column to order by.
     * @param string $direction The direction to order the result set in. Defaults to 'asc'.
     * @return $this The current instance of the class.
     */
    public function orderBy($column, $direction = 'asc')
    {
        $this->sql .= " ORDER BY $this->table.$column $direction";
        return $this;
    }

    /**
     * Groups the query results by a specified column.
     *
     * @param string $column The column to group by.
     * @return $this The current instance of the class.
     */
    public function groupBy($column)
    {
        $this->sql .= " GROUP BY $this->table.$column";
        return $this;
    }

    /**
     * Executes the SQL statement and returns all rows as an array of objects.
     *
     * @throws PDOException if an error occurs during the execution of the SQL statement.
     * @return array an array containing all rows fetched as objects.
     */
    public function get()
    {
        try {
            $this->stmt = self::getConnection()->prepare($this->sql);
            $this->stmt->execute();
            $result = $this->stmt->fetchAll(PDO::FETCH_OBJ);
            $this->sql = '';
            return $result;
        } catch (PDOException $e) {
            $this->setError('get', $e->getMessage());
        }
    }

    /**
     * Executes the SQL statement and returns the first row of the result as an object.
     *
     * @throws PDOException If an error occurs while executing the statement.
     * @return object|null The first row of the result as an object, or null if there are no rows.
     */
    public function first()
    {
        try {
            $this->stmt = self::getConnection()->prepare($this->sql);
            $this->stmt->execute();
            $result = $this->stmt->fetch(PDO::FETCH_OBJ);
            $this->sql = '';
            return $result;
        } catch (PDOException $e) {
            $this->setError('first', $e->getMessage());
        }
    }

    /**
     * Retrieves the last inserted ID from the database.
     *
     * @return string The last inserted ID.
     */
    public function lastInsertId()
    {
        return self::getConnection()->lastInsertId($this->table);
    }


    /**
     * Retrieves the count of records in the table.
     *
     * @throws PDOException when there is an error executing the SQL query.
     * @return int The count of records in the table.
     */
    public function count(): string
    {
        try {
            $countSql = "SELECT COUNT(*) FROM $this->table";
            $countSql .= $this->sql;
            $this->stmt = self::getConnection()->prepare($countSql);
            $this->stmt->execute();
            $count = $this->stmt->fetchColumn();
            $this->sql = '';
            return $count;
        } catch (PDOException $e) {
            $this->setError('count', $e->getMessage());
        }
    }


    /**
     * Executes the insert operation.
     *
     * @throws PDOException Error in insert {table}: {error message}
     * @return bool Returns true if the insert operation is successful, false otherwise.
     */
    private function executeInsert()
    {
        try {
            $columns = array_keys($this->data);
            $values = array_values($this->data);
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
            $this->data = null;
            return $statement->rowCount() > 0;
        } catch (PDOException $e) {
            $this->setError('executeInsert', $e->getMessage());
        }
    }

    private  function setError($err, $message)
    {

        $error = new Errors('TitanOrm', 'errors.log', 'debug');
        $msg = sprintf("%s  [%s] - %s", date("Y-m-d H:i:s"), "Error in $err " . $this->table, $message);
        $error->warning($msg);
    }
}
