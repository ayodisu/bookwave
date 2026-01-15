<?php
require_once '../core/Controller.php';
require_once '../app/Models/Product.php';
require_once '../app/Models/Cart.php';

class HomeController extends Controller
{
    private $productModel;
    private $cartModel;

    public function __construct()
    {
        $this->productModel = new Product();
        $this->cartModel = new Cart();
    }

    /**
     * Homepage (public - no login required)
     */
    public function index()
    {
        $message = null;

        // Handle add to cart
        if ($this->isPost()) {
            if (!$this->verifyCsrf()) {
                $message = ['type' => 'error', 'text' => 'Invalid request.'];
            } elseif (!$this->isLoggedIn()) {
                $this->redirect('login');
            } else {
                $userId = $_SESSION['user_id'];
                $productName = trim($_POST['product_name'] ?? '');
                $productPrice = (int)($_POST['product_price'] ?? 0);
                $productImage = trim($_POST['product_image'] ?? '');
                $productQuantity = max(1, (int)($_POST['product_quantity'] ?? 1));

                if ($this->cartModel->exists($userId, $productName)) {
                    $message = ['type' => 'info', 'text' => 'Already added to cart!'];
                } else {
                    $this->cartModel->add([
                        'user_id' => $userId,
                        'name' => $productName,
                        'price' => $productPrice,
                        'quantity' => $productQuantity,
                        'image' => $productImage
                    ]);
                    $message = ['type' => 'success', 'text' => 'Product added to cart!'];
                }
            }
        }

        $products = $this->productModel->latest(6);
        $cartCount = $this->isLoggedIn() ? $this->cartModel->countByUser($_SESSION['user_id']) : 0;

        $this->view('home/index', [
            'title' => 'Home',
            'products' => $products,
            'cartCount' => $cartCount,
            'message' => $message
        ]);
    }
}
