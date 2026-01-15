<?php
include 'config.php';

// Require user to be logged in
require_login();
$user_id = $_SESSION['user_id'];

$message = [];

// Update cart quantity
if (isset($_POST['update_cart'])) {
   if (!verify_csrf($_POST['csrf_token'] ?? '')) {
      $message[] = 'Invalid request.';
   } else {
      $cart_id = (int)$_POST['cart_id'];
      $cart_quantity = (int)$_POST['cart_quantity'];

      if ($cart_quantity < 1) {
         $cart_quantity = 1;
      }

      $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
      $stmt->bind_param("iii", $cart_quantity, $cart_id, $user_id);
      $stmt->execute();
      $stmt->close();
      $message[] = 'Cart quantity updated!';
   }
}

// Delete single item from cart
if (isset($_GET['delete'])) {
   $delete_id = (int)$_GET['delete'];

   $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
   $stmt->bind_param("ii", $delete_id, $user_id);
   $stmt->execute();
   $stmt->close();
   redirect('cart.php');
}

// Delete all items from cart
if (isset($_GET['delete_all'])) {
   $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
   $stmt->bind_param("i", $user_id);
   $stmt->execute();
   $stmt->close();
   redirect('cart.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Cart - BookWave</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>

<body>

   <?php include 'header.php'; ?>

   <div class="heading">
      <h3>Shopping Cart</h3>
      <p><a href="home.php">Home</a> / Cart</p>
   </div>

   <section class="shopping-cart">
      <h1 class="title">Products Added</h1>
      <div class="box-container">
         <?php
         $grand_total = 0;

         $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
         $stmt->bind_param("i", $user_id);
         $stmt->execute();
         $result = $stmt->get_result();

         if ($result->num_rows > 0) {
            while ($fetch_cart = $result->fetch_assoc()) {
               $sub_total = $fetch_cart['quantity'] * $fetch_cart['price'];
               $grand_total += $sub_total;
         ?>
               <div class="box">
                  <a href="cart.php?delete=<?php echo $fetch_cart['id']; ?>" class="fas fa-times" onclick="return confirm('Delete this from cart?');"></a>
                  <img src="uploaded_img/<?php echo e($fetch_cart['image']); ?>" alt="">
                  <div class="name"><?php echo e($fetch_cart['name']); ?></div>
                  <div class="price"><?php echo format_price($fetch_cart['price']); ?></div>
                  <form action="" method="post">
                     <?php echo csrf_field(); ?>
                     <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
                     <input type="number" min="1" name="cart_quantity" value="<?php echo $fetch_cart['quantity']; ?>">
                     <input type="submit" name="update_cart" value="Update" class="option-btn">
                  </form>
                  <div class="sub-total">Sub total: <span><?php echo format_price($sub_total); ?></span></div>
               </div>
         <?php
            }
         } else {
            echo '<p class="empty">Your cart is empty</p>';
         }
         $stmt->close();
         ?>
      </div>

      <div style="margin-top: 2rem; text-align:center;">
         <a href="cart.php?delete_all" class="delete-btn <?php echo ($grand_total > 0) ? '' : 'disabled'; ?>" onclick="return confirm('Delete all from cart?');">Delete All</a>
      </div>

      <div class="cart-total">
         <p>Grand Total: <span><?php echo format_price($grand_total); ?></span></p>
         <div class="flex">
            <a href="shop.php" class="option-btn">Continue Shopping</a>
            <a href="checkout.php" class="btn <?php echo ($grand_total > 0) ? '' : 'disabled'; ?>">Proceed to Checkout</a>
         </div>
      </div>
   </section>

   <?php include 'footer.php'; ?>
   <script src="js/script.js"></script>
</body>

</html>