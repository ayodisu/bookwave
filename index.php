<?php
include 'config.php';

$message = [];

// Handle add to cart
if (isset($_POST['add_to_cart'])) {
   if (!is_logged_in()) {
      $message[] = 'Please login to add items to cart!';
   } elseif (!verify_csrf($_POST['csrf_token'] ?? '')) {
      $message[] = 'Invalid request.';
   } else {
      $user_id = $_SESSION['user_id'];
      $product_name = trim($_POST['product_name']);
      $product_price = (int)$_POST['product_price'];
      $product_image = trim($_POST['product_image']);
      $product_quantity = (int)$_POST['product_quantity'];

      if ($product_quantity < 1) {
         $product_quantity = 1;
      }

      // Check if already in cart
      $stmt = $conn->prepare("SELECT id FROM cart WHERE name = ? AND user_id = ?");
      $stmt->bind_param("si", $product_name, $user_id);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
         $message[] = 'Already added to cart!';
      } else {
         $stmt = $conn->prepare("INSERT INTO cart (user_id, name, price, quantity, image) VALUES (?, ?, ?, ?, ?)");
         $stmt->bind_param("isiss", $user_id, $product_name, $product_price, $product_quantity, $product_image);
         $stmt->execute();
         $message[] = 'Product added to cart!';
      }
      $stmt->close();
   }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>BookWave - Your Book Store</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>

<body>

   <header class="header">
      <div class="header-1">
         <div class="flex">
            <div class="share">
               <a href="#" class="fab fa-facebook-f"></a>
               <a href="#" class="fab fa-twitter"></a>
               <a href="#" class="fab fa-instagram"></a>
               <a href="#" class="fab fa-linkedin"></a>
            </div>
            <p>
               <?php if (is_logged_in()): ?>
                  Welcome, <?php echo e($_SESSION['user_name']); ?> | <a href="logout.php">Logout</a>
               <?php else: ?>
                  <a href="login.php">Login</a> | <a href="register.php">Register</a>
               <?php endif; ?>
            </p>
         </div>
      </div>
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
                  $cart_count = $stmt->get_result()->fetch_assoc()['count'];
                  $stmt->close();
                  ?>
                  <a href="cart.php"><i class="fas fa-shopping-cart"></i> <span>(<?php echo $cart_count; ?>)</span></a>
               <?php endif; ?>
            </div>
            <?php if (is_logged_in()): ?>
               <div class="user-box">
                  <p>Username: <span><?php echo e($_SESSION['user_name']); ?></span></p>
                  <p>Email: <span><?php echo e($_SESSION['user_email']); ?></span></p>
                  <a href="logout.php" class="delete-btn">Logout</a>
               </div>
            <?php endif; ?>
         </div>
      </div>
   </header>

   <?php display_messages($message, 'info'); ?>

   <section class="home">
      <div class="content">
         <h3>Hand Picked Books to Your Door</h3>
         <p>Discover your next favorite book from our curated collection. Quality reads delivered straight to you.</p>
         <a href="about.php" class="white-btn">Discover More</a>
      </div>
   </section>

   <section class="products">
      <h1 class="title">Latest Products</h1>
      <div class="box-container">
         <?php
         $result = $conn->query("SELECT * FROM products LIMIT 6");

         if ($result->num_rows > 0) {
            while ($fetch_products = $result->fetch_assoc()) {
         ?>
               <form action="" method="post" class="box">
                  <?php echo csrf_field(); ?>
                  <img class="image" src="uploaded_img/<?php echo e($fetch_products['image']); ?>" alt="">
                  <div class="name"><?php echo e($fetch_products['name']); ?></div>
                  <div class="price"><?php echo format_price($fetch_products['price']); ?></div>
                  <input type="number" min="1" name="product_quantity" value="1" class="qty">
                  <input type="hidden" name="product_name" value="<?php echo e($fetch_products['name']); ?>">
                  <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                  <input type="hidden" name="product_image" value="<?php echo e($fetch_products['image']); ?>">
                  <input type="submit" value="Add to Cart" name="add_to_cart" class="btn">
               </form>
         <?php
            }
         } else {
            echo '<p class="empty">No products added yet!</p>';
         }
         ?>
      </div>
      <div class="load-more" style="margin-top: 2rem; text-align:center">
         <a href="shop.php" class="option-btn">Load More</a>
      </div>
   </section>

   <section class="about">
      <div class="flex">
         <div class="image">
            <img src="images/about-img.jpg" alt="">
         </div>
         <div class="content">
            <h3>About Us</h3>
            <p>We are passionate about connecting readers with their next great book. Our curated collection features the best titles across all genres.</p>
            <a href="about.php" class="btn">Read More</a>
         </div>
      </div>
   </section>

   <section class="home-contact">
      <div class="content">
         <h3>Have Any Questions?</h3>
         <p>We're here to help! Reach out to us with any questions about our books or services.</p>
         <a href="contact.php" class="white-btn">Contact Us</a>
      </div>
   </section>

   <?php include 'footer.php'; ?>
   <script src="js/script.js"></script>
</body>

</html>