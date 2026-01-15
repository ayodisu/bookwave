<?php

namespace Admin;

require_once '../core/Controller.php';
require_once '../app/Models/User.php';

class UserController extends \Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new \User();
    }

    /**
     * Users list
     */
    public function index()
    {
        $this->requireAdmin();

        $message = null;

        // Handle delete
        if ($this->isPost() && isset($_POST['delete_user'])) {
            if (!$this->verifyCsrf()) {
                $message = ['type' => 'error', 'text' => 'Invalid request.'];
            } else {
                $userId = (int)($_POST['user_id'] ?? 0);

                // Don't delete self
                if ($userId === $_SESSION['admin_id']) {
                    $message = ['type' => 'error', 'text' => 'Cannot delete yourself!'];
                } else {
                    $this->userModel->delete($userId);
                    $message = ['type' => 'success', 'text' => 'User deleted!'];
                }
            }
        }

        $users = $this->userModel->findAll();

        $this->view('admin/users', [
            'title' => 'Manage Users',
            'users' => $users,
            'message' => $message
        ], 'admin');
    }

    /**
     * User Orders
     */
    public function orders()
    {
        $this->requireAdmin();
        $userId = (int)($_GET['id'] ?? 0);
        $user = $this->userModel->find($userId);

        if (!$user) {
            $this->redirect('admin/users');
        }

        require_once '../app/Models/Order.php';
        $orderModel = new \Order();

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
                    $orderModel->updateStatus($orderId, $status);
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
                $orderModel->delete($orderId);
                $message = ['type' => 'success', 'text' => 'Order deleted!'];
            }
        }

        $orders = $orderModel->getByUser($userId);

        $this->view('admin/user_orders', [
            'title' => 'User Orders',
            'user' => $user,
            'orders' => $orders,
            'message' => $message
        ], 'admin');
    }
}
