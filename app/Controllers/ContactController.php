<?php
require_once '../core/Controller.php';
require_once '../app/Models/Message.php';
require_once '../app/Models/Cart.php';

class ContactController extends Controller
{
    private $messageModel;
    private $cartModel;

    public function __construct()
    {
        $this->messageModel = new Message();
        $this->cartModel = new Cart();
    }

    /**
     * Contact page
     */
    public function index()
    {
        $message = null;
        $success = false;

        if ($this->isPost()) {
            if (!$this->verifyCsrf()) {
                $message = ['type' => 'error', 'text' => 'Invalid request.'];
            } else {
                $name = trim($_POST['name'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $number = trim($_POST['number'] ?? '');
                $msg = trim($_POST['message'] ?? '');

                // Validation
                if (strlen($name) < 2) {
                    $message = ['type' => 'error', 'text' => 'Please enter a valid name.'];
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $message = ['type' => 'error', 'text' => 'Please enter a valid email.'];
                } elseif (empty($msg)) {
                    $message = ['type' => 'error', 'text' => 'Please enter a message.'];
                } else {
                    $userId = $this->isLoggedIn() ? $_SESSION['user_id'] : 0;

                    $result = $this->messageModel->create([
                        'user_id' => $userId,
                        'name' => $name,
                        'email' => $email,
                        'number' => $number,
                        'message' => $msg
                    ]);

                    if ($result) {
                        $message = ['type' => 'success', 'text' => 'Message sent successfully!'];
                        $success = true;
                    } else {
                        $message = ['type' => 'error', 'text' => 'Failed to send message.'];
                    }
                }
            }
        }

        $cartCount = $this->isLoggedIn() ? $this->cartModel->countByUser($_SESSION['user_id']) : 0;

        $this->view('contact/index', [
            'title' => 'Contact Us',
            'cartCount' => $cartCount,
            'message' => $message,
            'success' => $success
        ]);
    }
}
