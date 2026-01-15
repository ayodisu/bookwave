<?php

namespace Admin;

require_once '../core/Controller.php';
require_once '../app/Models/Product.php';

class ProductController extends \Controller
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new \Product();
    }

    /**
     * Products list
     */
    public function index()
    {
        $this->requireAdmin();

        $message = null;

        // Handle add product
        if ($this->isPost() && isset($_POST['add_product'])) {
            $message = $this->handleAdd();
        }

        // Handle update product
        if ($this->isPost() && isset($_POST['update_product'])) {
            $message = $this->handleUpdate();
        }

        // Handle delete product
        if ($this->isPost() && isset($_POST['delete_product'])) {
            $message = $this->handleDelete();
        }

        $products = $this->productModel->findAll();
        $editProduct = null;

        if (isset($_GET['edit'])) {
            $editProduct = $this->productModel->find((int)$_GET['edit']);
        }

        $this->view('admin/products', [
            'title' => 'Manage Products',
            'products' => $products,
            'editProduct' => $editProduct,
            'message' => $message
        ], 'admin');
    }

    private function handleAdd()
    {
        if (!$this->verifyCsrf()) {
            return ['type' => 'error', 'text' => 'Invalid request.'];
        }

        $name = trim($_POST['name'] ?? '');
        $price = (int)($_POST['price'] ?? 0);

        if (empty($name) || $price <= 0) {
            return ['type' => 'error', 'text' => 'Please fill all fields correctly.'];
        }

        // Handle image upload
        $image = $this->handleImageUpload();
        if (is_array($image)) {
            return $image; // Error message
        }

        $this->productModel->create([
            'name' => $name,
            'price' => $price,
            'image' => $image
        ]);

        return ['type' => 'success', 'text' => 'Product added successfully!'];
    }

    private function handleUpdate()
    {
        if (!$this->verifyCsrf()) {
            return ['type' => 'error', 'text' => 'Invalid request.'];
        }

        $id = (int)($_POST['product_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $price = (int)($_POST['price'] ?? 0);

        if ($id <= 0 || empty($name) || $price <= 0) {
            return ['type' => 'error', 'text' => 'Please fill all fields correctly.'];
        }

        $data = ['name' => $name, 'price' => $price];

        // Handle image upload if new image provided
        if (!empty($_FILES['image']['name'])) {
            $image = $this->handleImageUpload();
            if (is_array($image)) {
                return $image;
            }
            $data['image'] = $image;

            // Delete old image
            $oldProduct = $this->productModel->find($id);
            if ($oldProduct && file_exists('uploaded_img/' . $oldProduct['image'])) {
                unlink('uploaded_img/' . $oldProduct['image']);
            }
        }

        $this->productModel->update($id, $data);
        return ['type' => 'success', 'text' => 'Product updated successfully!'];
    }

    private function handleDelete()
    {
        if (!$this->verifyCsrf()) {
            return ['type' => 'error', 'text' => 'Invalid request.'];
        }

        $id = (int)($_POST['product_id'] ?? 0);

        if ($id <= 0) {
            return ['type' => 'error', 'text' => 'Invalid product.'];
        }

        // Delete image
        $product = $this->productModel->find($id);
        if ($product && file_exists('uploaded_img/' . $product['image'])) {
            unlink('uploaded_img/' . $product['image']);
        }

        $this->productModel->delete($id);
        return ['type' => 'success', 'text' => 'Product deleted successfully!'];
    }

    private function handleImageUpload()
    {
        if (empty($_FILES['image']['name'])) {
            return ['type' => 'error', 'text' => 'Please select an image.'];
        }

        $file = $_FILES['image'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        // Validate MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) {
            return ['type' => 'error', 'text' => 'Invalid image type. Allowed: JPG, PNG, GIF, WebP'];
        }

        if ($file['size'] > $maxSize) {
            return ['type' => 'error', 'text' => 'Image too large. Max size: 2MB'];
        }

        // Generate unique filename
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('product_') . '.' . $ext;

        if (move_uploaded_file($file['tmp_name'], 'uploaded_img/' . $filename)) {
            return $filename;
        }

        return ['type' => 'error', 'text' => 'Failed to upload image.'];
    }
}
