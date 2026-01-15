<?php
include 'config.php';

// Require admin login
require_admin();

$message = [];

// Update order payment status
if (isset($_POST['update_order'])) {
   if (!verify_csrf($_POST['csrf_token'] ?? '')) {
      $message[] = 'Invalid request.';
   } else {
      $order_id = (int)$_POST['order_id'];
      $update_payment = trim($_POST['update_payment']);

      // Validate payment status
      $valid_statuses = ['pending', 'completed', 'cancelled'];
      if (in_array($update_payment, $valid_statuses)) {
         $stmt = $conn->prepare("UPDATE orders SET payment_status = ? WHERE id = ?");
         $stmt->bind_param("si", $update_payment, $order_id);
         $stmt->execute();
         $stmt->close();
         $message[] = 'Payment status updated successfully!';
      } else {
         $message[] = 'Invalid payment status.';
      }
   }
}

// Delete order
if (isset($_GET['delete'])) {
   $delete_id = (int)$_GET['delete'];

   $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
   $stmt->bind_param("i", $delete_id);
   $stmt->execute();
   $stmt->close();
   redirect('admin_orders.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders - Admin Panel</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">
</head>

<body>

   <?php include 'admin_header.php'; ?>

   <?php display_messages($message, 'success'); ?>

   <section class="orders">
      <h1 class="title">Placed Orders</h1>
      <div class="box-container">
         <?php
         $result = $conn->query("SELECT * FROM orders ORDER BY id DESC");

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
                  <p>User ID: <span><?php echo $order['user_id']; ?></span></p>
                  <p>Placed On: <span><?php echo e($order['placed_on']); ?></span></p>
                  <p>Name: <span><?php echo e($order['name']); ?></span></p>
                  <p>Number: <span><?php echo e($order['number']); ?></span></p>
                  <p>Email: <span><?php echo e($order['email']); ?></span></p>
                  <p>Address: <span><?php echo e($order['address']); ?></span></p>
                  <p>Total Products: <span><?php echo e($order['total_products']); ?></span></p>
                  <p>Total Price: <span><?php echo format_price($order['total_price']); ?></span></p>
                  <p>Payment Method: <span><?php echo e($order['method']); ?></span></p>
                  <p>Status: <span <?php echo $status_class; ?>><?php echo ucfirst(e($order['payment_status'])); ?></span></p>
                  <form action="" method="post">
                     <?php echo csrf_field(); ?>
                     <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                     <select name="update_payment">
                        <option value="" disabled selected><?php echo ucfirst(e($order['payment_status'])); ?></option>
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                     </select>
                     <input type="submit" value="Update" name="update_order" class="option-btn">
                     <a href="admin_orders.php?delete=<?php echo $order['id']; ?>" onclick="return confirm('Delete this order?');" class="delete-btn">Delete</a>
                  </form>
               </div>
         <?php
            }
         } else {
            echo '<p class="empty">No orders placed yet!</p>';
         }
         ?>
      </div>
   </section>

   <script src="js/admin_script.js"></script>
</body>

</html>