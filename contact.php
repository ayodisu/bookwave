<?php
include 'config.php';

$message = [];
$success = false;

// Handle contact form submission
if (isset($_POST['send'])) {
   if (!verify_csrf($_POST['csrf_token'] ?? '')) {
      $message[] = 'Invalid request.';
   } else {
      $name = trim($_POST['name']);
      $email = trim($_POST['email']);
      $number = trim($_POST['number']);
      $msg = trim($_POST['message']);

      // Validate inputs
      if (strlen($name) < 2) {
         $message[] = 'Please enter a valid name.';
      } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         $message[] = 'Please enter a valid email.';
      } elseif (empty($msg)) {
         $message[] = 'Please enter a message.';
      } else {
         // Get user_id if logged in, otherwise use 0
         $user_id = is_logged_in() ? $_SESSION['user_id'] : 0;

         $stmt = $conn->prepare("INSERT INTO message (user_id, name, email, number, message) VALUES (?, ?, ?, ?, ?)");
         $stmt->bind_param("issss", $user_id, $name, $email, $number, $msg);

         if ($stmt->execute()) {
            $message[] = 'Message sent successfully!';
            $success = true;
         } else {
            $message[] = 'Failed to send message. Please try again.';
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
   <title>Contact Us - BookWave</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>

<body>

   <?php include 'header.php'; ?>

   <?php display_messages($message, $success ? 'success' : 'error'); ?>

   <div class="heading">
      <h3>Contact Us</h3>
      <p><a href="home.php">Home</a> / Contact</p>
   </div>

   <section class="contact">
      <form action="" method="post" class="contact-form">
         <?php echo csrf_field(); ?>
         <h3>Say Something!</h3>
         <input type="text" name="name" required placeholder="Enter your name" class="box" value="<?php echo e($_SESSION['user_name'] ?? ''); ?>">
         <input type="email" name="email" required placeholder="Enter your email" class="box" value="<?php echo e($_SESSION['user_email'] ?? ''); ?>">
         <input type="tel" name="number" required placeholder="Enter your phone number" class="box">
         <textarea name="message" class="box" placeholder="Enter your message" cols="30" rows="10" required></textarea>
         <button type="submit" name="send" class="btn submit-btn">
            <span class="btn-text">Send Message</span>
            <span class="btn-loading" style="display:none;"><i class="fas fa-spinner fa-spin"></i> Sending...</span>
         </button>
      </form>
   </section>

   <?php include 'footer.php'; ?>
   <script src="js/script.js"></script>
   <script>
      document.querySelector('.contact-form').addEventListener('submit', function() {
         const btn = this.querySelector('.submit-btn');
         const btnText = btn.querySelector('.btn-text');
         const btnLoading = btn.querySelector('.btn-loading');
         btn.disabled = true;
         btnText.style.display = 'none';
         btnLoading.style.display = 'inline';
      });
   </script>
</body>

</html>