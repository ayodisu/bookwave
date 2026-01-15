<?php
// Get cart count
$headerCartCount = $cartCount ?? 0;
?>
<header class="header">
    <div class="header-container">
        <!-- Logo -->
        <a href="<?php echo url(''); ?>" class="logo">
            <i class="fas fa-book-open"></i>
            <span>BookWave</span>
        </a>

        <!-- Navigation -->
        <nav class="navbar" id="navbar">
            <a href="<?php echo url('home'); ?>" class="nav-link">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a href="<?php echo url('shop'); ?>" class="nav-link">
                <i class="fas fa-store"></i>
                <span>Shop</span>
            </a>
            <a href="<?php echo url('about'); ?>" class="nav-link">
                <i class="fas fa-info-circle"></i>
                <span>About</span>
            </a>
            <a href="<?php echo url('contact'); ?>" class="nav-link">
                <i class="fas fa-envelope"></i>
                <span>Contact</span>
            </a>
            <?php if (is_logged_in()): ?>
                <a href="<?php echo url('orders'); ?>" class="nav-link">
                    <i class="fas fa-box"></i>
                    <span>Orders</span>
                </a>
            <?php endif; ?>
        </nav>

        <!-- Right Section -->
        <div class="header-actions">
            <!-- Search Button -->
            <a href="<?php echo url('search'); ?>" class="action-btn" title="Search">
                <i class="fas fa-search"></i>
            </a>

            <?php if (is_logged_in()): ?>
                <!-- Cart Button -->
                <a href="<?php echo url('cart'); ?>" class="action-btn cart-btn" title="Cart">
                    <i class="fas fa-shopping-cart"></i>
                    <?php if ($headerCartCount > 0): ?>
                        <span class="cart-badge"><?php echo $headerCartCount; ?></span>
                    <?php endif; ?>
                </a>

                <!-- User Dropdown -->
                <div class="user-dropdown">
                    <button class="action-btn user-btn" id="user-btn">
                        <i class="fas fa-user"></i>
                    </button>
                    <div class="dropdown-menu" id="dropdown-menu">
                        <div class="dropdown-header">
                            <div class="user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="user-info">
                                <span class="user-name"><?php echo e($_SESSION['user_name']); ?></span>
                                <span class="user-email"><?php echo e($_SESSION['user_email']); ?></span>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="<?php echo url('orders'); ?>" class="dropdown-item">
                            <i class="fas fa-box"></i>
                            <span>My Orders</span>
                        </a>
                        <a href="<?php echo url('cart'); ?>" class="dropdown-item">
                            <i class="fas fa-shopping-cart"></i>
                            <span>My Cart</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="<?php echo url('logout'); ?>" class="dropdown-item logout-item">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Auth Buttons -->
                <a href="<?php echo url('login'); ?>" class="auth-btn login">Login</a>
                <a href="<?php echo url('register'); ?>" class="auth-btn register">Sign Up</a>
            <?php endif; ?>

            <!-- Mobile Menu Toggle -->
            <button class="mobile-toggle" id="mobile-toggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
</header>

<script>
    // User dropdown toggle
    const userBtn = document.getElementById('user-btn');
    const dropdownMenu = document.getElementById('dropdown-menu');
    if (userBtn && dropdownMenu) {
        userBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownMenu.classList.toggle('active');
        });
        document.addEventListener('click', () => {
            dropdownMenu.classList.remove('active');
        });
    }

    // Mobile menu toggle
    const mobileToggle = document.getElementById('mobile-toggle');
    const navbar = document.getElementById('navbar');
    if (mobileToggle && navbar) {
        mobileToggle.addEventListener('click', () => {
            navbar.classList.toggle('active');
            mobileToggle.querySelector('i').classList.toggle('fa-bars');
            mobileToggle.querySelector('i').classList.toggle('fa-times');
        });
    }
</script>