<section class="dashboard">
    <h1 class="title">Dashboard</h1>
    <div class="box-container">
        <div class="box">
            <h3><?php echo $stats['totalProducts']; ?></h3>
            <p>Total Products</p>
        </div>
        <div class="box">
            <h3><?php echo $stats['totalOrders']; ?></h3>
            <p>Total Orders</p>
        </div>
        <div class="box">
            <h3><?php echo $stats['pendingOrders']; ?></h3>
            <p>Pending Orders</p>
        </div>
        <div class="box">
            <h3><?php echo $stats['completedOrders']; ?></h3>
            <p>Completed Orders</p>
        </div>
        <div class="box">
            <h3><?php echo $stats['totalUsers']; ?></h3>
            <p>Total Users</p>
        </div>
        <div class="box">
            <h3><?php echo $stats['totalAdmins']; ?></h3>
            <p>Total Admins</p>
        </div>
        <div class="box">
            <h3><?php echo $stats['totalMessages']; ?></h3>
            <p>Messages</p>
        </div>
        <div class="box">
            <h3><?php echo format_price($stats['totalRevenue']); ?></h3>
            <p>Total Revenue</p>
        </div>
    </div>
</section>