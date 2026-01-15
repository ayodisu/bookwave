<?php
include 'config.php';

// Redirect if already logged in
if (is_logged_in()) {
   redirect('home.php');
}
if (is_admin_logged_in()) {
   redirect('admin_page.php');
}

$message = [];
$message_type = 'error';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   // Verify CSRF token
   if (!verify_csrf($_POST['csrf_token'] ?? '')) {
      $message[] = 'Invalid request. Please try again.';
   } else {
      $email = trim($_POST['email']);
      $password = $_POST['password'];

      // Use prepared statement to prevent SQL injection
      $stmt = $conn->prepare("SELECT id, name, email, password, user_type FROM users WHERE email = ?");
      $stmt->bind_param("s", $email);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
         $row = $result->fetch_assoc();

         // Verify password using password_verify
         if (password_verify($password, $row['password'])) {
            if ($row['user_type'] == 'admin') {
               $_SESSION['admin_name'] = $row['name'];
               $_SESSION['admin_email'] = $row['email'];
               $_SESSION['admin_id'] = $row['id'];
               redirect('admin_page.php');
            } else {
               $_SESSION['user_name'] = $row['name'];
               $_SESSION['user_email'] = $row['email'];
               $_SESSION['user_id'] = $row['id'];
               redirect('home.php');
            }
         } else {
            $message[] = 'Incorrect email or password!';
         }
      } else {
         $message[] = 'Incorrect email or password!';
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
   <title>Login - BookWave</title>
   <script src="https://cdn.tailwindcss.com"></script>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>

<body>

   <!-- Modal Popup -->
   <?php if (!empty($message)): ?>
      <div id="messageModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" style="background: rgba(0,0,0,0.5);">
         <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all animate-[modalSlide_0.3s_ease-out]">
            <!-- Modal Header -->
            <div class="p-6 text-center">
               <!-- Error Icon -->
               <div class="mx-auto w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mb-4">
                  <i class="fas fa-exclamation-circle text-red-500 text-3xl"></i>
               </div>

               <!-- Title -->
               <h3 class="text-2xl font-bold text-gray-800 mb-2">Login Failed</h3>

               <!-- Messages -->
               <?php foreach ($message as $msg): ?>
                  <p class="text-gray-600 text-lg"><?php echo e($msg); ?></p>
               <?php endforeach; ?>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 pb-6 text-center">
               <button onclick="closeModal()" class="px-8 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors duration-200 shadow-lg hover:shadow-xl">
                  Try Again
               </button>
            </div>
         </div>
      </div>

      <style>
         @keyframes modalSlide {
            from {
               opacity: 0;
               transform: scale(0.9) translateY(-20px);
            }

            to {
               opacity: 1;
               transform: scale(1) translateY(0);
            }
         }
      </style>

      <script>
         function closeModal() {
            document.getElementById('messageModal').style.display = 'none';
         }

         // Close modal when clicking outside
         document.getElementById('messageModal').addEventListener('click', function(e) {
            if (e.target === this) {
               closeModal();
            }
         });

         // Close modal with Escape key
         document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
               closeModal();
            }
         });
      </script>
   <?php endif; ?>

   <div class="form-container">
      <form action="" method="post" class="auth-form">
         <?php echo csrf_field(); ?>
         <h3>Login Now</h3>
         <input type="email" name="email" placeholder="Enter your email" required class="box">
         <input type="password" name="password" placeholder="Enter your password" required class="box">
         <button type="submit" class="btn submit-btn">
            <span class="btn-text">Login Now</span>
            <span class="btn-loading" style="display:none;"><i class="fas fa-spinner fa-spin"></i> Logging in...</span>
         </button>
         <p>Don't have an account? <a href="register.php">Register now</a></p>
      </form>
   </div>

   <script>
      document.querySelector('.auth-form').addEventListener('submit', function() {
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