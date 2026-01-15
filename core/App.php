<?php

/**
 * Application Bootstrap
 * Initializes the application and handles routing
 */

class App
{
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        // Check for admin routes
        if (isset($url[0]) && $url[0] === 'admin') {
            array_shift($url); // Remove 'admin'
            $this->handleAdminRoute($url);
        } else {
            $this->handleRoute($url);
        }
    }

    protected function handleRoute($url)
    {
        // Map URLs to controllers
        $routes = [
            '' => ['HomeController', 'index'],
            'home' => ['HomeController', 'index'],
            'login' => ['AuthController', 'login'],
            'register' => ['AuthController', 'register'],
            'logout' => ['AuthController', 'logout'],
            'shop' => ['ShopController', 'index'],
            'cart' => ['CartController', 'index'],
            'cart/add' => ['CartController', 'add'],
            'cart/update' => ['CartController', 'update'],
            'cart/delete' => ['CartController', 'delete'],
            'cart/clear' => ['CartController', 'clear'],
            'checkout' => ['OrderController', 'checkout'],
            'orders' => ['OrderController', 'index'],
            'contact' => ['ContactController', 'index'],
            'about' => ['PageController', 'about'],
            'search' => ['ShopController', 'search'],
        ];

        $path = implode('/', $url);

        if (isset($routes[$path])) {
            $this->controller = $routes[$path][0];
            $this->method = $routes[$path][1];
        } elseif (isset($url[0]) && isset($routes[$url[0]])) {
            $this->controller = $routes[$url[0]][0];
            $this->method = $routes[$url[0]][1];
            array_shift($url);
            $this->params = $url;
        }

        $this->loadController();
    }

    protected function handleAdminRoute($url)
    {
        $routes = [
            '' => ['Admin\\DashboardController', 'index'],
            'dashboard' => ['Admin\\DashboardController', 'index'],
            'products' => ['Admin\\ProductController', 'index'],
            'products/add' => ['Admin\\ProductController', 'add'],
            'products/update' => ['Admin\\ProductController', 'update'],
            'products/delete' => ['Admin\\ProductController', 'delete'],
            'orders' => ['Admin\\OrderController', 'index'],
            'orders/update' => ['Admin\\OrderController', 'update'],
            'orders/delete' => ['Admin\\OrderController', 'delete'],
            'users' => ['Admin\\UserController', 'index'],
            'messages' => ['Admin\\MessageController', 'index'],
        ];

        $path = implode('/', $url);

        if (isset($routes[$path])) {
            $this->controller = $routes[$path][0];
            $this->method = $routes[$path][1];
        } elseif (isset($url[0]) && isset($routes[$url[0]])) {
            $this->controller = $routes[$url[0]][0];
            $this->method = $routes[$url[0]][1];
            array_shift($url);
            $this->params = $url;
        } else {
            $this->controller = 'Admin\\DashboardController';
            $this->method = 'index';
        }

        $this->loadController('Admin/');
    }

    protected function loadController($prefix = '')
    {
        $controllerFile = '../app/Controllers/' . str_replace('\\', '/', $this->controller) . '.php';

        if (file_exists($controllerFile)) {
            require_once $controllerFile;

            $controllerClass = $this->controller;
            $this->controller = new $controllerClass();

            if (method_exists($this->controller, $this->method)) {
                call_user_func_array([$this->controller, $this->method], $this->params);
            } else {
                $this->error404();
            }
        } else {
            $this->error404();
        }
    }

    protected function parseUrl()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }

    protected function error404()
    {
        http_response_code(404);
        require_once '../app/Views/errors/404.php';
        exit;
    }
}
