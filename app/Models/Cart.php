<?php
require_once '../core/Model.php';

class Cart extends Model
{
    protected $table = 'cart';

    /**
     * Get cart items for user
     */
    public function getByUser($userId)
    {
        return $this->where('user_id', $userId);
    }

    /**
     * Count items in cart
     */
    public function countByUser($userId)
    {
        return $this->count("user_id = {$userId}");
    }

    /**
     * Check if product is in cart
     */
    public function exists($userId, $productName)
    {
        $stmt = $this->prepare("SELECT id FROM cart WHERE user_id = ? AND name = ?");
        $stmt->bind_param("is", $userId, $productName);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    /**
     * Add item to cart
     */
    public function add($data)
    {
        $stmt = $this->prepare("INSERT INTO cart (user_id, name, price, quantity, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isiss", $data['user_id'], $data['name'], $data['price'], $data['quantity'], $data['image']);
        return $stmt->execute();
    }

    /**
     * Update cart quantity
     */
    public function updateQuantity($cartId, $quantity, $userId)
    {
        $stmt = $this->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
        $stmt->bind_param("iii", $quantity, $cartId, $userId);
        return $stmt->execute();
    }

    /**
     * Delete cart item
     */
    public function deleteItem($cartId, $userId)
    {
        $stmt = $this->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $cartId, $userId);
        return $stmt->execute();
    }

    /**
     * Clear user's cart
     */
    public function clearCart($userId)
    {
        return $this->deleteWhere('user_id', $userId);
    }

    /**
     * Get cart total
     */
    public function getTotal($userId)
    {
        $items = $this->getByUser($userId);
        $total = 0;
        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}
