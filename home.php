<?php
include 'config.php';

// Require user login for home page (authenticated area)
require_login();
$user_id = $_SESSION['user_id'];

$message = [];

// Handle add to cart
if (isset($_POST['add_to_cart'])) {
   if (!verify_csrf($_POST['csrf_token'] ?? '')) {
      $message[] = 'Invalid request.';
   } else {
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
   <title>Home - BookWave</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>

<body>

   <?php include 'header.php'; ?>

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
         $result = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT 6");

         if ($result->num_rows > 0) {
            while ($product = $result->fetch_assoc()) {
         ?>
               <form action="" method="post" class="box">
                  <?php echo csrf_field(); ?>
                  <img class="image" src="uploaded_img/<?php echo e($product['image']); ?>" alt="<?php echo e($product['name']); ?>">
                  <div class="name"><?php echo e($product['name']); ?></div>
                  <div class="price"><?php echo format_price($product['price']); ?></div>
                  <input type="number" min="1" name="product_quantity" value="1" class="qty">
                  <input type="hidden" name="product_name" value="<?php echo e($product['name']); ?>">
                  <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                  <input type="hidden" name="product_image" value="<?php echo e($product['image']); ?>">
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