<div class="heading">
    <h3>My Orders</h3>
    <p><a href="<?php echo url('home'); ?>">Home</a> / Orders</p>
</div>

<section class="orders">
    <h1 class="title">Your Orders</h1>
    <div class="box-container">
        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
                <?php
                $statusClass = '';
                if ($order['payment_status'] == 'completed') $statusClass = 'style="color: green;"';
                if ($order['payment_status'] == 'cancelled') $statusClass = 'style="color: red;"';
                ?>
                <div class="box">
                    <p>Placed On: <span><?php echo e($order['placed_on']); ?></span></p>
                    <p>Name: <span><?php echo e($order['name']); ?></span></p>
                    <p>Number: <span><?php echo e($order['number']); ?></span></p>
                    <p>Email: <span><?php echo e($order['email']); ?></span></p>
                    <p>Address: <span><?php echo e($order['address']); ?></span></p>
                    <p>Payment Method: <span><?php echo e($order['method']); ?></span></p>
                    <p>Products: <span><?php echo e($order['total_products']); ?></span></p>
                    <p>Total Price: <span><?php echo format_price($order['total_price']); ?></span></p>
                    <p>Payment Status: <span <?php echo $statusClass; ?>><?php echo ucfirst(e($order['payment_status'])); ?></span></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty">No orders placed yet! <a href="<?php echo url('shop'); ?>">Start shopping</a></p>
        <?php endif; ?>
    </div>
</section>