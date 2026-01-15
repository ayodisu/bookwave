<?php
require_once '../core/Model.php';

class Order extends Model
{
    protected $table = 'orders';

    /**
     * Create new order
     */
    public function create($data)
    {
        $stmt = $this->prepare("INSERT INTO orders (user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "issssssss",
            $data['user_id'],
            $data['name'],
            $data['number'],
            $data['email'],
            $data['method'],
            $data['address'],
            $data['total_products'],
            $data['total_price'],
            $data['placed_on']
        );
        return $stmt->execute();
    }

    /**
     * Get orders by user
     */
    public function getByUser($userId)
    {
        $stmt = $this->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Update payment status
     */
    public function updateStatus($orderId, $status)
    {
        $stmt = $this->prepare("UPDATE orders SET payment_status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $orderId);
        return $stmt->execute();
    }

    /**
     * Get total by status
     */
    public function getTotalByStatus($status)
    {
        $result = $this->query("SELECT SUM(total_price) as total FROM orders WHERE payment_status = '{$status}'");
        return $result->fetch_assoc()['total'] ?? 0;
    }

    /**
     * Get all orders
     */
    public function getAll()
    {
        return $this->findAll('id DESC');
    }
}
