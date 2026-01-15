<?php
include 'config.php';

// Require user to be logged in
require_login();
$user_id = $_SESSION['user_id'];

$message = [];
$order_placed = false;

if (isset($_POST['order_btn'])) {
   if (!verify_csrf($_POST['csrf_token'] ?? '')) {
      $message[] = 'Invalid request.';
   } else {
      $name = trim($_POST['name']);
      $number = trim($_POST['number']);
      $email = trim($_POST['email']);
      $method = trim($_POST['method']);
      $flat = trim($_POST['flat']);
      $street = trim($_POST['street']);
      $city = trim($_POST['city']);
      $state = trim($_POST['state']);
      $country = trim($_POST['country']);
      $pin_code = trim($_POST['pin_code']);

      $address = "Flat no. {$flat}, {$street}, {$city}, {$state}, {$country} - {$pin_code}";
      $placed_on = date('d-M-Y');

      // Get cart items
      $cart_total = 0;
      $cart_products = [];

      $stmt = $conn->prepare("SELECT name, price, quantity FROM cart WHERE user_id = ?");
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $result = $stmt->get_result();

      while ($cart_item = $result->fetch_assoc()) {
         $cart_products[] = $cart_item['name'] . ' (' . $cart_item['quantity'] . ')';
         $cart_total += ($cart_item['price'] * $cart_item['quantity']);
      }
      $stmt->close();

      if ($cart_total == 0) {
         $message[] = 'Your cart is empty!';
      } else {
         $total_products = implode(', ', $cart_products);

         // Insert order
         $stmt = $conn->prepare("INSERT INTO orders (user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
         $stmt->bind_param("issssssis", $user_id, $name, $number, $email, $method, $address, $total_products, $cart_total, $placed_on);

         if ($stmt->execute()) {
            // Clear cart
            $stmt2 = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
            $stmt2->bind_param("i", $user_id);
            $stmt2->execute();
            $stmt2->close();

            $message[] = 'Order placed successfully!';
            $order_placed = true;
         } else {
            $message[] = 'Failed to place order. Please try again.';
            error_log("Order error: " . $conn->error);
         }
         $stmt->close();
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout - BookWave</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>

<body>

   <?php include 'header.php'; ?>

   <div class="heading">
      <h3>Checkout</h3>
      <p><a href="home.php">Home</a> / Checkout</p>
   </div>

   <?php display_messages($message, $order_placed ? 'success' : 'error'); ?>

   <section class="display-order">
      <?php
      $grand_total = 0;

      $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
         while ($fetch_cart = $result->fetch_assoc()) {
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
      ?>
            <p><?php echo e($fetch_cart['name']); ?> <span>(<?php echo format_price($fetch_cart['price']); ?> x <?php echo $fetch_cart['quantity']; ?>)</span></p>
      <?php
         }
      } else {
         echo '<p class="empty">Your cart is empty</p>';
      }
      $stmt->close();
      ?>
      <div class="grand-total">Grand Total: <span><?php echo format_price($grand_total); ?></span></div>
   </section>

   <?php if ($grand_total > 0): ?>
      <section class="checkout">
         <form action="" method="post">
            <?php echo csrf_field(); ?>
            <h3>Place Your Order</h3>
            <div class="flex">
               <div class="inputBox">
                  <span>Your Name:</span>
                  <input type="text" name="name" required placeholder="Enter your name" value="<?php echo e($_SESSION['user_name'] ?? ''); ?>">
               </div>
               <div class="inputBox">
                  <span>Your Number:</span>
                  <input type="tel" name="number" required placeholder="Enter your phone number" pattern="[0-9]{10,15}">
               </div>
               <div class="inputBox">
                  <span>Your Email:</span>
                  <input type="email" name="email" required placeholder="Enter your email" value="<?php echo e($_SESSION['user_email'] ?? ''); ?>">
               </div>
               <div class="inputBox">
                  <span>Payment Method:</span>
                  <select name="method" required>
                     <option value="cash on delivery">Cash on Delivery</option>
                     <option value="bank transfer">Bank Transfer</option>
                     <option value="card payment">Card Payment</option>
                  </select>
               </div>
               <div class="inputBox">
                  <span>Flat/House No:</span>
                  <input type="text" name="flat" required placeholder="e.g. Flat 25">
               </div>
               <div class="inputBox">
                  <span>Street:</span>
                  <input type="text" name="street" required placeholder="e.g. Main Street">
               </div>
               <div class="inputBox">
                  <span>City:</span>
                  <input type="text" name="city" required placeholder="e.g. Lagos">
               </div>
               <div class="inputBox">
                  <span>State:</span>
                  <input type="text" name="state" required placeholder="e.g. Lagos State">
               </div>
               <div class="inputBox">
                  <span>Country:</span>
                  <input type="text" name="country" required placeholder="e.g. Nigeria">
               </div>
               <div class="inputBox">
                  <span>Pin Code:</span>
                  <input type="text" name="pin_code" required placeholder="e.g. 100001">
               </div>
            </div>
            <button type="submit" name="order_btn" class="btn submit-btn">
               <span class="btn-text">Place Order</span>
               <span class="btn-loading" style="display:none;"><i class="fas fa-spinner fa-spin"></i> Processing...</span>
            </button>
         </form>
      </section>
   <?php endif; ?>

   <?php include 'footer.php'; ?>
   <script src="js/script.js"></script>
   <script>
      const checkoutForm = document.querySelector('.checkout form');
      if (checkoutForm) {
         checkoutForm.addEventListener('submit', function() {
            const btn = this.querySelector('.submit-btn');
            const btnText = btn.querySelector('.btn-text');
            const btnLoading = btn.querySelector('.btn-loading');
            btn.disabled = true;
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline';
         });
      }
   </script>
</body>

</html>