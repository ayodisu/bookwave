<?php
require_once '../core/Model.php';

class Message extends Model
{
    protected $table = 'message';

    /**
     * Create new message
     */
    public function create($data)
    {
        $stmt = $this->prepare("INSERT INTO message (user_id, name, email, number, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $data['user_id'], $data['name'], $data['email'], $data['number'], $data['message']);
        return $stmt->execute();
    }

    /**
     * Get all messages
     */
    public function getAll()
    {
        return $this->findAll('id DESC');
    }
}
