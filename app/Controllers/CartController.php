<?php
require_once '../core/Controller.php';
require_once '../app/Models/Cart.php';

class CartController extends Controller
{
    private $cartModel;

    public function __construct()
    {
        $this->cartModel = new Cart();
    }

    /**
     * Cart page
     */
    public function index()
    {
        $this->requireLogin();
        $userId = $_SESSION['user_id'];

        $message = null;

        // Handle update/delete via POST
        if ($this->isPost()) {
            if (!$this->verifyCsrf()) {
                $message = ['type' => 'error', 'text' => 'Invalid request.'];
            } else {
                if (isset($_POST['update_qty'])) {
                    $cartId = (int)$_POST['cart_id'];
                    $qty = max(1, (int)$_POST['qty']);
                    $this->cartModel->updateQuantity($cartId, $qty, $userId);
                    $message = ['type' => 'success', 'text' => 'Cart updated!'];
                }

                if (isset($_POST['delete_item'])) {
                    $cartId = (int)$_POST['cart_id'];
                    $this->cartModel->deleteItem($cartId, $userId);
                    $message = ['type' => 'success', 'text' => 'Item removed from cart!'];
                }

                if (isset($_POST['clear_cart'])) {
                    $this->cartModel->clearCart($userId);
                    $message = ['type' => 'success', 'text' => 'Cart cleared!'];
                }
            }
        }

        $items = $this->cartModel->getByUser($userId);
        $total = $this->cartModel->getTotal($userId);
        $cartCount = count($items);

        $this->view('cart/index', [
            'title' => 'Cart',
            'items' => $items,
            'total' => $total,
            'cartCount' => $cartCount,
            'message' => $message
        ]);
    }

    /**
     * Add to cart (AJAX/POST)
     */
    public function add()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('login');
        }

        if ($this->isPost() && $this->verifyCsrf()) {
            $userId = $_SESSION['user_id'];
            $productName = trim($_POST['product_name'] ?? '');
            $productPrice = (int)($_POST['product_price'] ?? 0);
            $productImage = trim($_POST['product_image'] ?? '');
            $productQuantity = max(1, (int)($_POST['product_quantity'] ?? 1));

            if (!$this->cartModel->exists($userId, $productName)) {
                $this->cartModel->add([
                    'user_id' => $userId,
                    'name' => $productName,
                    'price' => $productPrice,
                    'quantity' => $productQuantity,
                    'image' => $productImage
                ]);
            }
        }

        $this->redirect('cart');
    }

    /**
     * Update cart quantity
     */
    public function update()
    {
        $this->requireLogin();

        if ($this->isPost() && $this->verifyCsrf()) {
            $userId = $_SESSION['user_id'];
            $cartId = (int)($_POST['cart_id'] ?? 0);
            $qty = max(1, (int)($_POST['qty'] ?? 1));
            $this->cartModel->updateQuantity($cartId, $qty, $userId);
        }

        $this->redirect('cart');
    }

    /**
     * Delete cart item
     */
    public function delete()
    {
        $this->requireLogin();

        if ($this->isPost() && $this->verifyCsrf()) {
            $userId = $_SESSION['user_id'];
            $cartId = (int)($_POST['cart_id'] ?? 0);
            $this->cartModel->deleteItem($cartId, $userId);
        }

        $this->redirect('cart');
    }

    /**
     * Clear cart
     */
    public function clear()
    {
        $this->requireLogin();

        if ($this->isPost() && $this->verifyCsrf()) {
            $this->cartModel->clearCart($_SESSION['user_id']);
        }

        $this->redirect('cart');
    }
}
