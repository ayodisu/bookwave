<?php
require_once '../core/Model.php';

class Product extends Model
{
    protected $table = 'products';

    /**
     * Get all products with pagination
     */
    public function paginate($page = 1, $perPage = 9)
    {
        $offset = ($page - 1) * $perPage;
        $stmt = $this->prepare("SELECT * FROM products ORDER BY id DESC LIMIT ? OFFSET ?");
        $stmt->bind_param("ii", $perPage, $offset);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get latest products
     */
    public function latest($limit = 6)
    {
        $stmt = $this->prepare("SELECT * FROM products ORDER BY id DESC LIMIT ?");
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Create new product
     */
    public function create($data)
    {
        $stmt = $this->prepare("INSERT INTO products (name, price, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $data['name'], $data['price'], $data['image']);
        return $stmt->execute();
    }

    /**
     * Update product
     */
    public function update($id, $data)
    {
        if (isset($data['image'])) {
            $stmt = $this->prepare("UPDATE products SET name = ?, price = ?, image = ? WHERE id = ?");
            $stmt->bind_param("sisi", $data['name'], $data['price'], $data['image'], $id);
        } else {
            $stmt = $this->prepare("UPDATE products SET name = ?, price = ? WHERE id = ?");
            $stmt->bind_param("sii", $data['name'], $data['price'], $id);
        }
        return $stmt->execute();
    }

    /**
     * Check if product name exists
     */
    public function nameExists($name, $excludeId = null)
    {
        if ($excludeId) {
            $stmt = $this->prepare("SELECT id FROM products WHERE name = ? AND id != ?");
            $stmt->bind_param("si", $name, $excludeId);
        } else {
            $stmt = $this->prepare("SELECT id FROM products WHERE name = ?");
            $stmt->bind_param("s", $name);
        }
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    /**
     * Search products
     */
    public function search($query)
    {
        $search = "%{$query}%";
        $stmt = $this->prepare("SELECT * FROM products WHERE name LIKE ? ORDER BY id DESC");
        $stmt->bind_param("s", $search);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
