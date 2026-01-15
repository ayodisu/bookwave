<?php if (isset($message) && $message): ?>
    <div class="message <?php echo $message['type']; ?>">
        <span><?php echo e($message['text']); ?></span>
        <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
    </div>
<?php endif; ?>

<section class="orders">
    <h1 class="title">All Orders</h1>
    <div class="box-container">
        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
                <?php
                $statusClass = '';
                if ($order['payment_status'] == 'completed') $statusClass = 'style="color: green;"';
                if ($order['payment_status'] == 'cancelled') $statusClass = 'style="color: red;"';
                ?>
                <div class="box">
                    <p>User ID: <span><?php echo $order['user_id']; ?></span></p>
                    <p>Placed On: <span><?php echo e($order['placed_on']); ?></span></p>
                    <p>Name: <span><?php echo e($order['name']); ?></span></p>
                    <p>Number: <span><?php echo e($order['number']); ?></span></p>
                    <p>Email: <span><?php echo e($order['email']); ?></span></p>
                    <p>Address: <span><?php echo e($order['address']); ?></span></p>
                    <p>Method: <span><?php echo e($order['method']); ?></span></p>
                    <p>Products: <span><?php echo e($order['total_products']); ?></span></p>
                    <p>Total: <span><?php echo format_price($order['total_price']); ?></span></p>
                    <p>Status: <span <?php echo $statusClass; ?>><?php echo ucfirst(e($order['payment_status'])); ?></span></p>

                    <form action="<?php echo url('admin/orders'); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                        <select name="status" class="drop-down">
                            <option value="pending" <?php echo $order['payment_status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="completed" <?php echo $order['payment_status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                            <option value="cancelled" <?php echo $order['payment_status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                        <button type="submit" name="update_status" class="option-btn">Update</button>
                    </form>

                    <form action="<?php echo url('admin/orders'); ?>" method="post" style="display:inline;">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                        <button type="submit" name="delete_order" class="delete-btn" onclick="return confirm('Delete this order?');">Delete</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty">No orders yet!</p>
        <?php endif; ?>
    </div>
</section>