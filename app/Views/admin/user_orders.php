<?php if (isset($message) && $message): ?>
    <div class="message <?php echo $message['type']; ?>">
        <span><?php echo e($message['text']); ?></span>
        <i class="fas fa-times" onclick="this.parentElement.remove();" style="cursor: pointer;"></i>
    </div>
<?php endif; ?>

<div class="admin-card">
    <div class="card-header">
        <div style="display:flex; align-items:center; gap:1rem;">
            <a href="<?php echo url('admin/users'); ?>" class="btn" style="background:var(--gray); padding: 0.5rem 1rem;"><i class="fas fa-arrow-left"></i> Back</a>
            <h3 class="card-title">Orders for: <span style="color:var(--primary);"><?php echo e($user['name']); ?></span> <span style="font-weight:400; color:var(--gray); font-size:0.875rem;">(<?php echo e($user['email']); ?>)</span></h3>
        </div>
        <div style="font-size: 0.875rem; color: #64748b;">
            Total: <strong><?php echo count($orders); ?></strong>
        </div>
    </div>

    <div class="table-container">
        <?php if (!empty($orders)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td style="font-size: 0.875rem; color: #64748b;"><?php echo e($order['placed_on']); ?></td>
                            <td style="font-weight: 600; color: var(--dark);"><?php echo format_price($order['total_price']); ?></td>
                            <td>
                                <form action="<?php echo url('admin/users/orders?id=' . $user['id']); ?>" method="post" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <select name="status" onchange="this.form.submit()" class="badge <?php
                                                                                                        echo $order['payment_status'] == 'completed' ? 'badge-success' : ($order['payment_status'] == 'cancelled' ? 'badge-danger' : 'badge-warning');
                                                                                                        ?>" style="border:none; cursor:pointer; -webkit-appearance: none; -moz-appearance: none; text-indent: 1px; text-overflow: '';">
                                        <option value="pending" <?php echo $order['payment_status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="completed" <?php echo $order['payment_status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                        <option value="cancelled" <?php echo $order['payment_status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <span class="badge badge-gray"><?php echo e($order['method']); ?></span>
                            </td>
                            <td>
                                <form action="<?php echo url('admin/users/orders?id=' . $user['id']); ?>" method="post" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <button type="submit" name="delete_order" class="action-btn delete" onclick="return confirm('Delete this order?');" title="Delete Order">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-shopping-bag" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                <p>No orders found for this user.</p>
            </div>
        <?php endif; ?>
    </div>
</div>