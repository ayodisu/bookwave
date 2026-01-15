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
}
