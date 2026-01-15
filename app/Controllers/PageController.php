<?php
require_once '../core/Controller.php';
require_once '../app/Models/Cart.php';

class PageController extends Controller
{
    private $cartModel;

    public function __construct()
    {
        $this->cartModel = new Cart();
    }

    /**
     * About page
     */
    public function about()
    {
        $cartCount = $this->isLoggedIn() ? $this->cartModel->countByUser($_SESSION['user_id']) : 0;

        $this->view('pages/about', [
            'title' => 'About Us',
            'cartCount' => $cartCount
        ]);
    }
}
