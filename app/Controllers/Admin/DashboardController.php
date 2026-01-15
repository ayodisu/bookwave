<?php

namespace Admin;

require_once '../core/Controller.php';
require_once '../app/Models/Product.php';
require_once '../app/Models/Order.php';
require_once '../app/Models/User.php';
require_once '../app/Models/Message.php';

class DashboardController extends \Controller
{
    private $productModel;
    private $orderModel;
    private $userModel;
    private $messageModel;

    public function __construct()
    {
        $this->productModel = new \Product();
        $this->orderModel = new \Order();
        $this->userModel = new \User();
        $this->messageModel = new \Message();
    }

    /**
     * Admin dashboard
     */
    public function index()
    {
        $this->requireAdmin();

        $stats = [
            'totalProducts' => $this->productModel->count(),
            'totalOrders' => $this->orderModel->count(),
            'totalUsers' => $this->userModel->countByType('user'),
            'totalAdmins' => $this->userModel->countByType('admin'),
            'totalMessages' => $this->messageModel->count(),
            'pendingOrders' => $this->orderModel->count("payment_status = 'pending'"),
            'completedOrders' => $this->orderModel->count("payment_status = 'completed'"),
            'totalRevenue' => $this->orderModel->getTotalByStatus('completed')
        ];

        $this->view('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'stats' => $stats
        ], 'admin');
    }
}
