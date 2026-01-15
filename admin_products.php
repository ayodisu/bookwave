<?php
include 'config.php';

// Require admin login
require_admin();

$message = [];

// Allowed file types for upload
$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$max_file_size = 2 * 1024 * 1024; // 2MB

// Add new product
if (isset($_POST['add_product'])) {
   if (!verify_csrf($_POST['csrf_token'] ?? '')) {
      $message[] = 'Invalid request.';
   } else {
      $name = trim($_POST['name']);
      $price = (int)$_POST['price'];

      // Validate image FIRST (before inserting product)
      if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
         $message[] = 'Please select an image to upload.';
      } else {
         $image = $_FILES['image'];
         $image_size = $image['size'];
         $image_tmp = $image['tmp_name'];

         // Validate file type
         $finfo = finfo_open(FILEINFO_MIME_TYPE);
         $mime_type = finfo_file($finfo, $image_tmp);
         finfo_close($finfo);

         if (!in_array($mime_type, $allowed_types)) {
            $message[] = 'Invalid file type. Only JPG, PNG, GIF, and WebP allowed.';
         } elseif ($image_size > $max_file_size) {
            $message[] = 'Image size is too large. Maximum 2MB allowed.';
         } else {
            // Check if product name already exists
            $stmt = $conn->prepare("SELECT id FROM products WHERE name = ?");
            $stmt->bind_param("s", $name);
            $stmt->execute();

            if ($stmt->get_result()->num_rows > 0) {
               $message[] = 'Product name already exists.';
            } else {
               // Generate unique filename to prevent overwrites
               $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
               $new_filename = uniqid('product_') . '.' . $extension;
               $upload_path = 'uploaded_img/' . $new_filename;

               if (move_uploaded_file($image_tmp, $upload_path)) {
                  $stmt = $conn->prepare("INSERT INTO products (name, price, image) VALUES (?, ?, ?)");
                  $stmt->bind_param("sis", $name, $price, $new_filename);

                  if ($stmt->execute()) {
                     $message[] = 'Product added successfully!';
                  } else {
                     $message[] = 'Failed to add product.';
                     unlink($upload_path); // Remove uploaded file on failure
                  }
               } else {
                  $message[] = 'Failed to upload image.';
               }
            }
            $stmt->close();
         }
      }
   }
}

// Delete product
if (isset($_GET['delete'])) {
   $delete_id = (int)$_GET['delete'];

   // Get image filename first
   $stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
   $stmt->bind_param("i", $delete_id);
   $stmt->execute();
   $result = $stmt->get_result();

   if ($row = $result->fetch_assoc()) {
      $image_path = 'uploaded_img/' . $row['image'];

      // Delete from database
      $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
      $stmt->bind_param("i", $delete_id);

      if ($stmt->execute()) {
         // Delete image file
         if (file_exists($image_path)) {
            unlink($image_path);
         }
      }
   }
   $stmt->close();
   redirect('admin_products.php');
}

// Update product
if (isset($_POST['update_product'])) {
   if (!verify_csrf($_POST['csrf_token'] ?? '')) {
      $message[] = 'Invalid request.';
   } else {
      $update_id = (int)$_POST['update_p_id'];
      $update_name = trim($_POST['update_name']);
      $update_price = (int)$_POST['update_price'];
      $update_old_image = $_POST['update_old_image'];

      // Update basic info
      $stmt = $conn->prepare("UPDATE products SET name = ?, price = ? WHERE id = ?");
      $stmt->bind_param("sii", $update_name, $update_price, $update_id);
      $stmt->execute();
      $stmt->close();

      // Handle image update if new image provided
      if (isset($_FILES['update_image']) && $_FILES['update_image']['error'] === UPLOAD_ERR_OK) {
         $image = $_FILES['update_image'];
         $image_tmp = $image['tmp_name'];
         $image_size = $image['size'];

         // Validate file type
         $finfo = finfo_open(FILEINFO_MIME_TYPE);
         $mime_type = finfo_file($finfo, $image_tmp);
         finfo_close($finfo);

         if (!in_array($mime_type, $allowed_types)) {
            $message[] = 'Invalid file type for new image.';
         } elseif ($image_size > $max_file_size) {
            $message[] = 'New image size is too large.';
         } else {
            $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
            $new_filename = uniqid('product_') . '.' . $extension;
            $upload_path = 'uploaded_img/' . $new_filename;

            if (move_uploaded_file($image_tmp, $upload_path)) {
               // Update database with new image
               $stmt = $conn->prepare("UPDATE products SET image = ? WHERE id = ?");
               $stmt->bind_param("si", $new_filename, $update_id);
               $stmt->execute();
               $stmt->close();

               // Delete old image
               $old_path = 'uploaded_img/' . $update_old_image;
               if (file_exists($old_path)) {
                  unlink($old_path);
               }
            }
         }
      }

      redirect('admin_products.php');
   }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products - Admin Panel</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/admin_style.css">
</head>

<body>

   <?php include 'admin_header.php'; ?>

   <?php display_messages($message, 'info'); ?>

   <section class="add-products">
      <h1 class="title">Shop Products</h1>
      <form action="" method="post" enctype="multipart/form-data">
         <?php echo csrf_field(); ?>
         <h3>Add Product</h3>
         <input type="text" name="name" class="box" placeholder="Enter product name" required>
         <input type="number" min="0" name="price" class="box" placeholder="Enter product price" required>
         <input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp" class="box" required>
         <input type="submit" value="Add Product" name="add_product" class="btn">
      </form>
   </section>

   <section class="show-products">
      <div class="box-container">
         <?php
         $result = $conn->query("SELECT * FROM products ORDER BY id DESC");

         if ($result->num_rows > 0) {
            while ($product = $result->fetch_assoc()) {
         ?>
               <div class="box">
                  <img src="uploaded_img/<?php echo e($product['image']); ?>" alt="<?php echo e($product['name']); ?>">
                  <div class="name"><?php echo e($product['name']); ?></div>
                  <div class="price"><?php echo format_price($product['price']); ?></div>
                  <a href="admin_products.php?update=<?php echo $product['id']; ?>" class="option-btn">Update</a>
                  <a href="admin_products.php?delete=<?php echo $product['id']; ?>" class="delete-btn" onclick="return confirm('Delete this product?');">Delete</a>
               </div>
         <?php
            }
         } else {
            echo '<p class="empty">No products added yet!</p>';
         }
         ?>
      </div>
   </section>

   <?php if (isset($_GET['update'])): ?>
      <section class="edit-product-form">
         <?php
         $update_id = (int)$_GET['update'];
         $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
         $stmt->bind_param("i", $update_id);
         $stmt->execute();
         $result = $stmt->get_result();

         if ($product = $result->fetch_assoc()):
         ?>
            <form action="" method="post" enctype="multipart/form-data">
               <?php echo csrf_field(); ?>
               <input type="hidden" name="update_p_id" value="<?php echo $product['id']; ?>">
               <input type="hidden" name="update_old_image" value="<?php echo e($product['image']); ?>">
               <img src="uploaded_img/<?php echo e($product['image']); ?>" alt="">
               <input type="text" name="update_name" value="<?php echo e($product['name']); ?>" class="box" required placeholder="Enter product name">
               <input type="number" name="update_price" value="<?php echo $product['price']; ?>" min="0" class="box" required placeholder="Enter product price">
               <input type="file" class="box" name="update_image" accept="image/jpeg,image/png,image/gif,image/webp">
               <input type="submit" value="Update" name="update_product" class="btn">
               <a href="admin_products.php" class="option-btn">Cancel</a>
            </form>
         <?php
         endif;
         $stmt->close();
         ?>
      </section>
   <?php endif; ?>

   <script src="js/admin_script.js"></script>
</body>

</html>