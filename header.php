<?php
// This file is included in other pages, config.php should already be included
?>

<?php if (!empty($message)): ?>
   <?php display_messages($message, 'info'); ?>
<?php endif; ?>

<header class="header">
   <div class="header-2">
      <div class="flex">
         <a href="index.php" class="logo">BookWave</a>
         <nav class="navbar">
            <a href="home.php">Home</a>
            <a href="about.php">About</a>
            <a href="shop.php">Shop</a>
            <a href="contact.php">Contact</a>
            <a href="orders.php">Orders</a>
         </nav>
         <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="search_page.php" class="fas fa-search"></a>
            <div id="user-btn" class="fas fa-user"></div>
            <?php if (is_logged_in()): ?>
               <?php
               $user_id = $_SESSION['user_id'];
               $stmt = $conn->prepare("SELECT COUNT(*) as count FROM cart WHERE user_id = ?");
               $stmt->bind_param("i", $user_id);
               $stmt->execute();
               $cart_rows_number = $stmt->get_result()->fetch_assoc()['count'];
               $stmt->close();
               ?>
               <a href="cart.php"><i class="fas fa-shopping-cart"></i> <span>(<?php echo $cart_rows_number; ?>)</span></a>
            <?php endif; ?>
         </div>
         <?php if (is_logged_in()): ?>
            <div class="user-box">
               <p>Username: <span><?php echo e($_SESSION['user_name']); ?></span></p>
               <p>Email: <span><?php echo e($_SESSION['user_email']); ?></span></p>
               <a href="logout.php" class="delete-btn">Logout</a>
            </div>
         <?php else: ?>
            <div class="user-box">
               <p>Please login to access your account</p>
               <a href="login.php" class="btn">Login</a>
            </div>
         <?php endif; ?>
      </div>
   </div>
</header>