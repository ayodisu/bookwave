<?php
// Admin header - config.php should already be included
?>

<?php if (!empty($message)): ?>
   <?php display_messages($message, 'info'); ?>
<?php endif; ?>

<header class="header">
   <nav class="navbar">
      <a href="admin_page.php"><i class="fas fa-home"></i><span>Dashboard</span></a>
      <a href="admin_products.php"><i class="fas fa-box"></i><span>Products</span></a>
      <a href="admin_orders.php"><i class="fas fa-shopping-cart"></i><span>Orders</span></a>
      <a href="admin_users.php"><i class="fas fa-users"></i><span>Users</span></a>
      <a href="admin_contacts.php"><i class="fas fa-envelope"></i><span>Messages</span></a>
   </nav>
   <div class="flex">
      <p>Welcome, <span><?php echo e($_SESSION['admin_name'] ?? 'Admin'); ?></span></p>
      <a href="logout.php" class="btn">Logout</a>
   </div>
</header>