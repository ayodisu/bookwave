<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo url('images/favicon.svg'); ?>" type="image/svg+xml">
    <title><?php echo e($title ?? 'Admin'); ?> - BookWave Admin</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo url('css/admin_style.css?v=' . time()); ?>">
</head>

<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <a href="<?php echo url('admin'); ?>" class="logo">
            <i class="fas fa-book-open"></i>
            <span>BookWave</span>
        </a>

        <nav class="nav-menu">
            <a href="<?php echo url('admin'); ?>" class="<?php echo ($title == 'Dashboard') ? 'active' : ''; ?>">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
            <a href="<?php echo url('admin/products'); ?>" class="<?php echo ($title == 'Products') ? 'active' : ''; ?>">
                <i class="fas fa-box"></i>
                <span>Products</span>
            </a>
            <a href="<?php echo url('admin/orders'); ?>" class="<?php echo ($title == 'Orders') ? 'active' : ''; ?>">
                <i class="fas fa-shopping-bag"></i>
                <span>Orders</span>
            </a>
            <a href="<?php echo url('admin/users'); ?>" class="<?php echo ($title == 'Users') ? 'active' : ''; ?>">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
            <a href="<?php echo url('admin/messages'); ?>" class="<?php echo ($title == 'Messages') ? 'active' : ''; ?>">
                <i class="fas fa-envelope"></i>
                <span>Messages</span>
            </a>
        </nav>

        <div class="logout-container">
            <a href="<?php echo url('logout'); ?>" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Header for Mobile & Actions -->
        <header class="top-header">
            <div class="header-left">
                <div class="menu-toggle" id="menu-toggle">
                    <i class="fas fa-bars"></i>
                </div>
                <div class="header-title">
                    <h1><?php echo e($title ?? 'Dashboard'); ?></h1>
                </div>
            </div>

            <div class="header-right">
                <a href="<?php echo url(''); ?>" target="_blank" class="view-site-btn" title="View Website">
                    <i class="fas fa-external-link-alt"></i>
                    <span>View Site</span>
                </a>

                <div class="admin-profile">
                    <div class="avatar">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="info">
                        <h3><?php echo e($_SESSION['admin_name'] ?? 'Admin'); ?></h3>
                        <span>Administrator</span>
                    </div>
                    <a href="<?php echo url('logout'); ?>" class="header-logout" title="Logout"><i class="fas fa-sign-out-alt"></i></a>
                </div>
            </div>
        </header>

        <div class="content-wrapper">
            <?php echo $content; ?>
        </div>
    </main>

    <script src="<?php echo url('js/admin_script.js'); ?>"></script>
    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const sidebar = document.querySelector('.sidebar');
        const mainContent = document.querySelector('.main-content');

        if (menuToggle) {
            menuToggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });
        }
    </script>
</body>

</html>