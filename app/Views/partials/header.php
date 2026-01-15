<?php
// Get cart count
$headerCartCount = $cartCount ?? 0;
?>
<header class="header">
    <div class="header-1">
        <div class="flex">
            <div class="share">
                <a href="#" class="fab fa-facebook-f"></a>
                <a href="#" class="fab fa-twitter"></a>
                <a href="#" class="fab fa-instagram"></a>
                <a href="#" class="fab fa-linkedin"></a>
            </div>
            <?php if (is_logged_in()): ?>
                <p>Welcome, <a href="<?php echo url('orders'); ?>"><?php echo e($_SESSION['user_name']); ?></a> | <a href="<?php echo url('logout'); ?>">Logout</a></p>
            <?php else: ?>
                <p><a href="<?php echo url('login'); ?>">Login</a> | <a href="<?php echo url('register'); ?>">Register</a></p>
            <?php endif; ?>
        </div>
    </div>
    <div class="header-2">
        <div class="flex">
            <a href="<?php echo url(''); ?>" class="logo">BookWave</a>
            <nav class="navbar">
                <a href="<?php echo url('home'); ?>">Home</a>
                <a href="<?php echo url('about'); ?>">About</a>
                <a href="<?php echo url('shop'); ?>">Shop</a>
                <a href="<?php echo url('contact'); ?>">Contact</a>
                <a href="<?php echo url('orders'); ?>">Orders</a>
            </nav>
            <div class="icons">
                <div id="menu-btn" class="fas fa-bars"></div>
                <a href="<?php echo url('search'); ?>" class="fas fa-search"></a>
                <?php if (is_logged_in()): ?>
                    <div id="user-btn" class="fas fa-user"></div>
                    <a href="<?php echo url('cart'); ?>">
                        <i class="fas fa-shopping-cart"></i>
                        <span>(<?php echo $headerCartCount; ?>)</span>
                    </a>
                <?php endif; ?>
            </div>
            <?php if (is_logged_in()): ?>
                <div class="user-box">
                    <p>Username: <span><?php echo e($_SESSION['user_name']); ?></span></p>
                    <p>Email: <span><?php echo e($_SESSION['user_email']); ?></span></p>
                    <a href="<?php echo url('logout'); ?>" class="delete-btn">Logout</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>