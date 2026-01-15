<?php

namespace Admin;

require_once '../core/Controller.php';
require_once '../app/Models/Order.php';

class OrderController extends \Controller
{
    private $orderModel;

    public function __construct()
    {
        $this->orderModel = new \Order();
    }

    /**
     * Orders list
     */
    public function index()
    {
        $this->requireAdmin();

        $message = null;

        // Handle update status
        if ($this->isPost() && isset($_POST['status'])) {
            if (!$this->verifyCsrf()) {
                $message = ['type' => 'error', 'text' => 'Invalid request.'];
            } else {
                $orderId = (int)($_POST['order_id'] ?? 0);
                $status = trim($_POST['status'] ?? '');

                $validStatuses = ['pending', 'completed', 'cancelled'];
                if (in_array($status, $validStatuses)) {
                    $this->orderModel->updateStatus($orderId, $status);
                    $message = ['type' => 'success', 'text' => 'Order status updated!'];
                }
            }
        }

        // Handle delete
        if ($this->isPost() && isset($_POST['delete_order'])) {
            if (!$this->verifyCsrf()) {
                $message = ['type' => 'error', 'text' => 'Invalid request.'];
            } else {
                $orderId = (int)($_POST['order_id'] ?? 0);
                $this->orderModel->delete($orderId);
                $message = ['type' => 'success', 'text' => 'Order deleted!'];
            }
        }

        $orders = $this->orderModel->getAll();

        $this->view('admin/orders', [
            'title' => 'Manage Orders',
            'orders' => $orders,
            'message' => $message
        ], 'admin');
    }
}
