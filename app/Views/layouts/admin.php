<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'Admin'); ?> - BookWave Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo url('css/admin_style.css'); ?>">
</head>

<body>

    <header class="header">
        <nav class="navbar">
            <a href="<?php echo url('admin'); ?>"><i class="fas fa-home"></i><span>Dashboard</span></a>
            <a href="<?php echo url('admin/products'); ?>"><i class="fas fa-box"></i><span>Products</span></a>
            <a href="<?php echo url('admin/orders'); ?>"><i class="fas fa-shopping-cart"></i><span>Orders</span></a>
            <a href="<?php echo url('admin/users'); ?>"><i class="fas fa-users"></i><span>Users</span></a>
            <a href="<?php echo url('admin/messages'); ?>"><i class="fas fa-envelope"></i><span>Messages</span></a>
        </nav>
        <div class="flex">
            <p>Welcome, <span><?php echo e($_SESSION['admin_name'] ?? 'Admin'); ?></span></p>
            <a href="<?php echo url('logout'); ?>" class="btn">Logout</a>
        </div>
    </header>

    <?php echo $content; ?>

    <script src="<?php echo url('js/admin_script.js'); ?>"></script>
</body>

</html>