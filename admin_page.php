<?php
include 'config.php';

// Require admin login
require_admin();
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Panel - BookWave</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">
</head>

<body>

   <?php include 'admin_header.php'; ?>

   <section class="dashboard">
      <h1 class="title">Dashboard</h1>
      <div class="box-container">
         <div class="box">
            <?php
            $result = $conn->query("SELECT SUM(total_price) as total FROM orders WHERE payment_status = 'pending'");
            $total_pendings = $result->fetch_assoc()['total'] ?? 0;
            ?>
            <h3><?php echo format_price($total_pendings); ?></h3>
            <p>Total Pendings</p>
         </div>

         <div class="box">
            <?php
            $result = $conn->query("SELECT SUM(total_price) as total FROM orders WHERE payment_status = 'completed'");
            $total_completed = $result->fetch_assoc()['total'] ?? 0;
            ?>
            <h3><?php echo format_price($total_completed); ?></h3>
            <p>Completed Payments</p>
         </div>

         <div class="box">
            <?php
            $result = $conn->query("SELECT COUNT(*) as count FROM orders");
            $number_of_orders = $result->fetch_assoc()['count'];
            ?>
            <h3><?php echo $number_of_orders; ?></h3>
            <p>Orders Placed</p>
         </div>

         <div class="box">
            <?php
            $result = $conn->query("SELECT COUNT(*) as count FROM products");
            $number_of_products = $result->fetch_assoc()['count'];
            ?>
            <h3><?php echo $number_of_products; ?></h3>
            <p>Products Added</p>
         </div>

         <div class="box">
            <?php
            $result = $conn->query("SELECT COUNT(*) as count FROM users WHERE user_type = 'user'");
            $number_of_users = $result->fetch_assoc()['count'];
            ?>
            <h3><?php echo $number_of_users; ?></h3>
            <p>Normal Users</p>
         </div>

         <div class="box">
            <?php
            $result = $conn->query("SELECT COUNT(*) as count FROM users WHERE user_type = 'admin'");
            $number_of_admins = $result->fetch_assoc()['count'];
            ?>
            <h3><?php echo $number_of_admins; ?></h3>
            <p>Admin Users</p>
         </div>

         <div class="box">
            <?php
            $result = $conn->query("SELECT COUNT(*) as count FROM users");
            $number_of_accounts = $result->fetch_assoc()['count'];
            ?>
            <h3><?php echo $number_of_accounts; ?></h3>
            <p>Total Accounts</p>
         </div>

         <div class="box">
            <?php
            $result = $conn->query("SELECT COUNT(*) as count FROM message");
            $number_of_messages = $result->fetch_assoc()['count'];
            ?>
            <h3><?php echo $number_of_messages; ?></h3>
            <p>New Messages</p>
         </div>
      </div>
   </section>

   <script src="js/admin_script.js"></script>
</body>

</html>