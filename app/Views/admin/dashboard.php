<section class="dashboard">
    <div class="dashboard-grid">
        <!-- Revenue -->
        <div class="widget-card revenue-widget">
            <div class="widget-icon" style="background: #ecfdf5; color: #10b981;">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="widget-info">
                <h3><?php echo format_price($stats['totalRevenue']); ?></h3>
                <p>Total Revenue</p>
            </div>
        </div>

        <!-- Orders -->
        <div class="widget-card orders-widget">
            <div class="widget-icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="widget-info">
                <h3><?php echo $stats['totalOrders']; ?></h3>
                <p>Total Orders</p>
            </div>
        </div>

        <!-- Products -->
        <div class="widget-card products-widget">
            <div class="widget-icon">
                <i class="fas fa-box"></i>
            </div>
            <div class="widget-info">
                <h3><?php echo $stats['totalProducts']; ?></h3>
                <p>Total Products</p>
            </div>
        </div>

        <!-- Users -->
        <div class="widget-card users-widget">
            <div class="widget-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="widget-info">
                <h3><?php echo $stats['totalUsers']; ?></h3>
                <p>Total Users</p>
            </div>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="widget-card">
            <div class="widget-icon" style="background: #fef9c3; color: #ca8a04;">
                <i class="fas fa-clock"></i>
            </div>
            <div class="widget-info">
                <h3><?php echo $stats['pendingOrders']; ?></h3>
                <p>Pending Orders</p>
            </div>
        </div>

        <div class="widget-card">
            <div class="widget-icon" style="background: #dbeafe; color: #2563eb;">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="widget-info">
                <h3><?php echo $stats['completedOrders']; ?></h3>
                <p>Completed Orders</p>
            </div>
        </div>

        <div class="widget-card">
            <div class="widget-icon" style="background: #f3f4f6; color: #4b5563;">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="widget-info">
                <h3><?php echo $stats['totalAdmins']; ?></h3>
                <p>Administrators</p>
            </div>
        </div>

        <div class="widget-card messages-widget">
            <div class="widget-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="widget-info">
                <h3><?php echo $stats['totalMessages']; ?></h3>
                <p>New Messages</p>
            </div>
        </div>
    </div>
</section>