<?php

/**
 * Base Model
 * All models extend this class for database operations
 */

class Model
{
    protected $conn;
    protected $table;

    public function __construct()
    {
        $this->conn = $this->getConnection();
    }

    /**
     * Get database connection
     */
    protected function getConnection()
    {
        static $conn = null;

        if ($conn === null) {
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            if ($conn->connect_error) {
                error_log("Database connection failed: " . $conn->connect_error);
                die("Connection failed. Please try again later.");
            }

            $conn->set_charset("utf8mb4");
        }

        return $conn;
    }

    /**
     * Find all records
     */
    public function findAll($orderBy = 'id DESC', $limit = null)
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy}";
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Find record by ID
     */
    public function find($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Find records with conditions
     */
    public function where($column, $value, $operator = '=')
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE {$column} {$operator} ?");
        $type = is_int($value) ? 'i' : 's';
        $stmt->bind_param($type, $value);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Find single record with condition
     */
    public function findWhere($column, $value)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE {$column} = ?");
        $type = is_int($value) ? 'i' : 's';
        $stmt->bind_param($type, $value);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Count records
     */
    public function count($where = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        $result = $this->conn->query($sql);
        return $result->fetch_assoc()['count'];
    }

    /**
     * Delete record by ID
     */
    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * Delete with condition
     */
    public function deleteWhere($column, $value)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE {$column} = ?");
        $type = is_int($value) ? 'i' : 's';
        $stmt->bind_param($type, $value);
        return $stmt->execute();
    }

    /**
     * Get last insert ID
     */
    public function lastInsertId()
    {
        return $this->conn->insert_id;
    }

    /**
     * Execute raw query
     */
    protected function query($sql)
    {
        return $this->conn->query($sql);
    }

    /**
     * Prepare statement
     */
    protected function prepare($sql)
    {
        return $this->conn->prepare($sql);
    }
}
