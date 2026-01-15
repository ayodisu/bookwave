<?php
require_once '../core/Controller.php';
require_once '../app/Models/Order.php';
require_once '../app/Models/Cart.php';

class OrderController extends Controller
{
    private $orderModel;
    private $cartModel;

    public function __construct()
    {
        $this->orderModel = new Order();
        $this->cartModel = new Cart();
    }

    /**
     * Checkout page
     */
    public function checkout()
    {
        $this->requireLogin();
        $userId = $_SESSION['user_id'];

        $message = null;
        $success = false;

        // Handle order submission
        if ($this->isPost()) {
            if (!$this->verifyCsrf()) {
                $message = ['type' => 'error', 'text' => 'Invalid request.'];
            } else {
                $name = trim($_POST['name'] ?? '');
                $number = trim($_POST['number'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $method = trim($_POST['method'] ?? '');
                $flat = trim($_POST['flat'] ?? '');
                $street = trim($_POST['street'] ?? '');
                $city = trim($_POST['city'] ?? '');
                $state = trim($_POST['state'] ?? '');
                $country = trim($_POST['country'] ?? '');
                $pinCode = trim($_POST['pin_code'] ?? '');

                $address = "$flat, $street, $city, $state, $country - $pinCode";

                // Get cart items
                $cartItems = $this->cartModel->getByUser($userId);
                $totalPrice = $this->cartModel->getTotal($userId);

                if (empty($cartItems)) {
                    $message = ['type' => 'error', 'text' => 'Your cart is empty!'];
                } else {
                    // Build products string
                    $products = [];
                    foreach ($cartItems as $item) {
                        $products[] = $item['name'] . ' (x' . $item['quantity'] . ')';
                    }
                    $totalProducts = implode(', ', $products);

                    // Validate constraints
                    if (strlen($number) > 12) {
                        $message = ['type' => 'error', 'text' => 'Phone number is too long (Max 12 digits).'];
                    } elseif (strlen($name) > 100) {
                        $message = ['type' => 'error', 'text' => 'Name is too long (Max 100 chars).'];
                    } elseif (strlen($email) > 100) {
                        $message = ['type' => 'error', 'text' => 'Email is too long (Max 100 chars).'];
                    } elseif (strlen($address) > 500) {
                        $message = ['type' => 'error', 'text' => 'Address is too long (Max 500 chars).'];
                    } elseif (strlen($totalProducts) > 1000) {
                        $message = ['type' => 'error', 'text' => 'Too many items in cart for one order. Please split order.'];
                    } else {
                        // Create order
                        $result = $this->orderModel->create([
                            'user_id' => $userId,
                            'name' => $name,
                            'number' => $number,
                            'email' => $email,
                            'method' => $method,
                            'address' => $address,
                            'total_products' => $totalProducts,
                            'total_price' => $totalPrice,
                            'placed_on' => date('Y-m-d H:i:s')
                        ]);

                        if ($result === true) {
                            $this->cartModel->clearCart($userId);
                            $_SESSION['toast'] = 'Order placed successfully!';
                            $this->redirect('orders');
                        } else {
                            $message = ['type' => 'error', 'text' => 'Failed to place order: ' . $result];
                        }
                    }
                }
            }
        }

        $cartItems = $this->cartModel->getByUser($userId);
        $grandTotal = $this->cartModel->getTotal($userId);
        $cartCount = count($cartItems);

        $this->view('orders/checkout', [
            'title' => 'Checkout',
            'cartItems' => $cartItems,
            'grandTotal' => $grandTotal,
            'cartCount' => $cartCount,
            'message' => $message,
            'success' => $success
        ]);
    }

    /**
     * Orders history page
     */
    public function index()
    {
        $this->requireLogin();
        $userId = $_SESSION['user_id'];

        $orders = $this->orderModel->getByUser($userId);
        $cartCount = $this->cartModel->countByUser($userId);

        $this->view('orders/index', [
            'title' => 'My Orders',
            'orders' => $orders,
            'cartCount' => $cartCount
        ]);
    }
}
