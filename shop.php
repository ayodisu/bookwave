<?php
include 'config.php';

$message = [];

// Pagination settings
$items_per_page = 9;
$current_page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($current_page - 1) * $items_per_page;

// Get total products count
$total_result = $conn->query("SELECT COUNT(*) as total FROM products");
$total_products = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_products / $items_per_page);

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
   <title>Shop - BookWave</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>

<body>

   <?php include 'header.php'; ?>

   <?php display_messages($message, 'info'); ?>

   <div class="heading">
      <h3>Our Shop</h3>
      <p><a href="home.php">Home</a> / Shop</p>
   </div>

   <section class="products">
      <h1 class="title">All Products</h1>
      <p style="text-align:center; font-size:1.6rem; color:var(--light-color); margin-bottom:2rem;">
         Showing <?php echo min($offset + 1, $total_products); ?>-<?php echo min($offset + $items_per_page, $total_products); ?> of <?php echo $total_products; ?> products
      </p>
      <div class="box-container">
         <?php
         $stmt = $conn->prepare("SELECT * FROM products ORDER BY id DESC LIMIT ? OFFSET ?");
         $stmt->bind_param("ii", $items_per_page, $offset);
         $stmt->execute();
         $result = $stmt->get_result();

         if ($result->num_rows > 0) {
            while ($product = $result->fetch_assoc()) {
         ?>
               <form action="" method="post" class="box product-form">
                  <?php echo csrf_field(); ?>
                  <img class="image" src="uploaded_img/<?php echo e($product['image']); ?>" alt="<?php echo e($product['name']); ?>">
                  <div class="name"><?php echo e($product['name']); ?></div>
                  <div class="price"><?php echo format_price($product['price']); ?></div>
                  <input type="number" min="1" name="product_quantity" value="1" class="qty">
                  <input type="hidden" name="product_name" value="<?php echo e($product['name']); ?>">
                  <input type="hidden" name="product_price" value="<?php echo $product['price']; ?>">
                  <input type="hidden" name="product_image" value="<?php echo e($product['image']); ?>">
                  <button type="submit" name="add_to_cart" class="btn submit-btn">
                     <span class="btn-text">Add to Cart</span>
                     <span class="btn-loading" style="display:none;"><i class="fas fa-spinner fa-spin"></i> Adding...</span>
                  </button>
               </form>
         <?php
            }
         } else {
            echo '<p class="empty">No products available yet!</p>';
         }
         $stmt->close();
         ?>
      </div>

      <?php if ($total_pages > 1): ?>
         <div class="pagination">
            <?php if ($current_page > 1): ?>
               <a href="?page=1" class="page-link">&laquo; First</a>
               <a href="?page=<?php echo $current_page - 1; ?>" class="page-link">&lsaquo; Prev</a>
            <?php endif; ?>

            <?php
            $start_page = max(1, $current_page - 2);
            $end_page = min($total_pages, $current_page + 2);

            for ($i = $start_page; $i <= $end_page; $i++):
            ?>
               <a href="?page=<?php echo $i; ?>" class="page-link <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                  <?php echo $i; ?>
               </a>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
               <a href="?page=<?php echo $current_page + 1; ?>" class="page-link">Next &rsaquo;</a>
               <a href="?page=<?php echo $total_pages; ?>" class="page-link">Last &raquo;</a>
            <?php endif; ?>
         </div>
      <?php endif; ?>
   </section>

   <?php include 'footer.php'; ?>
   <script src="js/script.js"></script>
   <script>
      // Loading state for forms
      document.querySelectorAll('.product-form').forEach(form => {
         form.addEventListener('submit', function() {
            const btn = this.querySelector('.submit-btn');
            const btnText = btn.querySelector('.btn-text');
            const btnLoading = btn.querySelector('.btn-loading');
            btn.disabled = true;
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline';
         });
      });
   </script>
</body>

</html>