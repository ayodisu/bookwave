<?php
require_once '../core/Controller.php';
require_once '../app/Models/User.php';

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Login page
     */
    public function login()
    {
        // Redirect if already logged in
        if ($this->isLoggedIn()) {
            $this->redirect('home');
        }
        if ($this->isAdmin()) {
            $this->redirect('admin');
        }

        $errors = [];

        if ($this->isPost()) {
            if (!$this->verifyCsrf()) {
                $errors[] = 'Invalid request. Please try again.';
            } else {
                $email = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';

                $user = $this->userModel->findByEmail($email);

                if ($user && $this->userModel->verifyPassword($password, $user['password'])) {
                    if ($user['user_type'] === 'admin') {
                        $_SESSION['admin_id'] = $user['id'];
                        $_SESSION['admin_name'] = $user['name'];
                        $_SESSION['admin_email'] = $user['email'];
                        $this->toast('Welcome back, ' . $user['name'] . '!');
                        $this->redirect('admin');
                    } else {
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_name'] = $user['name'];
                        $_SESSION['user_email'] = $user['email'];
                        $this->toast('Welcome back, ' . $user['name'] . '!');
                        $this->redirect('home');
                    }
                } else {
                    $errors[] = 'Incorrect email or password!';
                }
            }
        }

        $this->view('auth/login', [
            'title' => 'Login',
            'errors' => $errors
        ], 'auth');
    }

    /**
     * Register page
     */
    public function register()
    {
        // Redirect if already logged in
        if ($this->isLoggedIn()) {
            $this->redirect('home');
        }

        $errors = [];

        if ($this->isPost()) {
            if (!$this->verifyCsrf()) {
                $errors[] = 'Invalid request. Please try again.';
            } else {
                $name = trim($_POST['name'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';
                $cpassword = $_POST['cpassword'] ?? '';

                // Validation
                if (strlen($name) < 2) {
                    $errors[] = 'Name must be at least 2 characters!';
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Please enter a valid email address!';
                } elseif (strlen($password) < 6) {
                    $errors[] = 'Password must be at least 6 characters!';
                } elseif ($password !== $cpassword) {
                    $errors[] = 'Passwords do not match!';
                } elseif ($this->userModel->findByEmail($email)) {
                    $errors[] = 'Email already registered!';
                } else {
                    // Create user
                    $result = $this->userModel->create([
                        'name' => $name,
                        'email' => $email,
                        'password' => $this->userModel->hashPassword($password),
                        'user_type' => 'user'
                    ]);

                    if ($result) {
                        // Auto-login
                        $user = $this->userModel->findByEmail($email);
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_name'] = $user['name'];
                        $_SESSION['user_email'] = $user['email'];
                        $this->toast('Welcome to BookWave, ' . $name . '!');
                        $this->redirect('home');
                    } else {
                        $errors[] = 'Registration failed. Please try again.';
                    }
                }
            }
        }

        $this->view('auth/register', [
            'title' => 'Register',
            'errors' => $errors
        ], 'auth');
    }

    /**
     * Logout
     */
    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}
