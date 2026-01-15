<?php

namespace Admin;

require_once '../core/Controller.php';
require_once '../app/Models/Message.php';

class MessageController extends \Controller
{
    private $messageModel;

    public function __construct()
    {
        $this->messageModel = new \Message();
    }

    /**
     * Messages list
     */
    public function index()
    {
        $this->requireAdmin();

        $message = null;

        // Handle delete
        if ($this->isPost() && isset($_POST['delete_message'])) {
            if (!$this->verifyCsrf()) {
                $message = ['type' => 'error', 'text' => 'Invalid request.'];
            } else {
                $messageId = (int)($_POST['message_id'] ?? 0);
                $this->messageModel->delete($messageId);
                $message = ['type' => 'success', 'text' => 'Message deleted!'];
            }
        }

        $messages = $this->messageModel->getAll();

        $this->view('admin/messages', [
            'title' => 'Messages',
            'messages' => $messages,
            'message' => $message
        ], 'admin');
    }
}
