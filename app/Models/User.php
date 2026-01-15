<?php
require_once '../core/Model.php';

class User extends Model
{
    protected $table = 'users';

    /**
     * Find user by email
     */
    public function findByEmail($email)
    {
        $stmt = $this->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    /**
     * Create new user
     */
    public function create($data)
    {
        $stmt = $this->prepare("INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $data['name'], $data['email'], $data['password'], $data['user_type']);
        return $stmt->execute();
    }

    /**
     * Verify password
     */
    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Hash password
     */
    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Get all users by type
     */
    public function getByType($type)
    {
        return $this->where('user_type', $type);
    }

    /**
     * Count users by type
     */
    public function countByType($type)
    {
        return $this->count("user_type = '{$type}'");
    }
}
