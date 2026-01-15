<?php
include 'config.php';

// Require user to be logged in
require_login();
$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>My Orders - BookWave</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>

<body>

   <?php include 'header.php'; ?>

   <div class="heading">
      <h3>My Orders</h3>
      <p><a href="home.php">Home</a> / Orders</p>
   </div>

   <section class="orders">
      <h1 class="title">Your Orders</h1>
      <div class="box-container">
         <?php
         $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
         $stmt->bind_param("i", $user_id);
         $stmt->execute();
         $result = $stmt->get_result();

         if ($result->num_rows > 0) {
            while ($order = $result->fetch_assoc()) {
               $status_class = '';
               if ($order['payment_status'] == 'completed') {
                  $status_class = 'style="color: green;"';
               } elseif ($order['payment_status'] == 'cancelled') {
                  $status_class = 'style="color: red;"';
               }
         ?>
               <div class="box">
                  <p>Placed On: <span><?php echo e($order['placed_on']); ?></span></p>
                  <p>Name: <span><?php echo e($order['name']); ?></span></p>
                  <p>Number: <span><?php echo e($order['number']); ?></span></p>
                  <p>Email: <span><?php echo e($order['email']); ?></span></p>
                  <p>Address: <span><?php echo e($order['address']); ?></span></p>
                  <p>Payment Method: <span><?php echo e($order['method']); ?></span></p>
                  <p>Products: <span><?php echo e($order['total_products']); ?></span></p>
                  <p>Total Price: <span><?php echo format_price($order['total_price']); ?></span></p>
                  <p>Payment Status: <span <?php echo $status_class; ?>><?php echo ucfirst(e($order['payment_status'])); ?></span></p>
               </div>
         <?php
            }
         } else {
            echo '<p class="empty">No orders placed yet!</p>';
         }
         $stmt->close();
         ?>
      </div>
   </section>

   <?php include 'footer.php'; ?>
   <script src="js/script.js"></script>
</body>

</html>