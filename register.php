<?php
include 'config.php';

// Redirect if already logged in
if (is_logged_in()) {
   redirect('home.php');
}

$message = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   // Verify CSRF token
   if (!verify_csrf($_POST['csrf_token'] ?? '')) {
      $message[] = 'Invalid request. Please try again.';
   } else {
      $name = trim($_POST['name']);
      $email = trim($_POST['email']);
      $password = $_POST['password'];
      $cpassword = $_POST['cpassword'];

      // Validate inputs
      if (strlen($name) < 2) {
         $message[] = 'Name must be at least 2 characters!';
      } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         $message[] = 'Please enter a valid email address!';
      } elseif (strlen($password) < 6) {
         $message[] = 'Password must be at least 6 characters!';
      } elseif ($password !== $cpassword) {
         $message[] = 'Passwords do not match!';
      } else {
         // Check if email already exists
         $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
         $stmt->bind_param("s", $email);
         $stmt->execute();
         $result = $stmt->get_result();

         if ($result->num_rows > 0) {
            $message[] = 'Email already registered!';
         } else {
            // Hash password securely
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, 'user')");
            $stmt->bind_param("sss", $name, $email, $password_hash);

            if ($stmt->execute()) {
               // Auto-login: Set session variables
               $new_user_id = $conn->insert_id;
               $_SESSION['user_id'] = $new_user_id;
               $_SESSION['user_name'] = $name;
               $_SESSION['user_email'] = $email;

               // Redirect to homepage
               redirect('home.php');
            } else {
               $message[] = 'Registration failed. Please try again.';
               error_log("Registration error: " . $conn->error);
            }
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
   <title>Register - BookWave</title>
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
                  <i class="fas fa-exclamation-triangle text-red-500 text-3xl"></i>
               </div>

               <!-- Title -->
               <h3 class="text-2xl font-bold text-gray-800 mb-2">Registration Error</h3>

               <!-- Messages -->
               <div class="space-y-2">
                  <?php foreach ($message as $msg): ?>
                     <p class="text-gray-600 text-lg"><?php echo e($msg); ?></p>
                  <?php endforeach; ?>
               </div>
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
         <h3>Register Now</h3>
         <input type="text" name="name" placeholder="Enter your name" required class="box" minlength="2">
         <input type="email" name="email" placeholder="Enter your email" required class="box">
         <input type="password" name="password" placeholder="Enter your password" required class="box" minlength="6">
         <input type="password" name="cpassword" placeholder="Confirm your password" required class="box">
         <button type="submit" class="btn submit-btn">
            <span class="btn-text">Register Now</span>
            <span class="btn-loading" style="display:none;"><i class="fas fa-spinner fa-spin"></i> Registering...</span>
         </button>
         <p>Already have an account? <a href="login.php">Login now</a></p>
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