<?php

/**
 * Base Controller
 * All controllers extend this class
 */

class Controller
{
    /**
     * Load a model
     */
    protected function model($model)
    {
        $modelFile = '../app/Models/' . $model . '.php';
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model();
        }
        return null;
    }

    /**
     * Load a view with data
     */
    protected function view($view, $data = [], $layout = 'main')
    {
        // Extract data to make variables available in view
        extract($data);

        // Start output buffering
        ob_start();

        $viewFile = '../app/Views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        }

        $content = ob_get_clean();

        // Load layout
        $layoutFile = '../app/Views/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            require_once $layoutFile;
        } else {
            echo $content;
        }
    }

    /**
     * Render view without layout
     */
    protected function renderPartial($view, $data = [])
    {
        extract($data);
        $viewFile = '../app/Views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        }
    }

    /**
     * Redirect to a URL
     */
    protected function redirect($url)
    {
        header('Location: ' . BASE_URL . '/' . ltrim($url, '/'));
        exit;
    }

    /**
     * Check if user is logged in
     */
    protected function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    /**
     * Check if admin is logged in
     */
    protected function isAdmin()
    {
        return isset($_SESSION['admin_id']);
    }

    /**
     * Require user to be logged in
     */
    protected function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('login');
        }
    }

    /**
     * Require admin to be logged in
     */
    protected function requireAdmin()
    {
        if (!$this->isAdmin()) {
            $this->redirect('login');
        }
    }

    /**
     * Verify CSRF token
     */
    protected function verifyCsrf()
    {
        $token = $_POST['csrf_token'] ?? '';
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Check if request is POST
     */
    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Set flash message
     */
    protected function setFlash($key, $message)
    {
        $_SESSION['flash'][$key] = $message;
    }

    /**
     * Get and clear flash message
     */
    protected function getFlash($key)
    {
        $message = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $message;
    }

    /**
     * Set toast message
     */
    protected function toast($message)
    {
        $_SESSION['toast'] = $message;
    }
}
