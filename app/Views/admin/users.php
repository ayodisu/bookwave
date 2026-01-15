<?php if (isset($message) && $message): ?>
    <div class="message <?php echo $message['type']; ?>">
        <span><?php echo e($message['text']); ?></span>
        <i class="fas fa-times" onclick="this.parentElement.remove();" style="cursor: pointer;"></i>
    </div>
<?php endif; ?>

<div class="admin-card">
    <div class="card-header">
        <h3 class="card-title">Registered Users</h3>
        <div style="font-size: 0.875rem; color: #64748b;">
            Total: <strong><?php echo count($users); ?></strong>
        </div>
    </div>

    <div class="table-container">
        <?php if (!empty($users)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 60px;">Avatar</th>
                        <th>User Details</th>
                        <th>Role</th>
                        <th>User ID</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <div style="width: 40px; height: 40px; background: #e0e7ff; color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600;">
                                    <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 500; font-size: 0.875rem;"><?php echo e($user['name']); ?></div>
                                <div style="font-size: 0.75rem; color: #64748b;"><?php echo e($user['email']); ?></div>
                            </td>
                            <td>
                                <span class="badge <?php echo $user['user_type'] == 'admin' ? 'badge-primary' : 'badge-gray'; ?>">
                                    <?php echo ucfirst(e($user['user_type'])); ?>
                                </span>
                            </td>
                            <td style="color: #64748b;">#<?php echo $user['id']; ?></td>
                            <td>
                                <a href="<?php echo url('admin/users/orders?id=' . $user['id']); ?>" class="action-btn edit" title="View Orders" style="margin-right: 0.5rem;">
                                    <i class="fas fa-box"></i>
                                </a>
                                <?php if ($user['id'] !== $_SESSION['admin_id']): ?>
                                    <form action="<?php echo url('admin/users'); ?>" method="post" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        <button type="submit" name="delete_user" class="action-btn delete" onclick="return confirm('Delete this user?');" title="Delete User">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span style="font-size: 0.75rem; color: #94a3b8;">(Current)</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-users" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                <p>No registered users found.</p>
            </div>
        <?php endif; ?>
    </div>
</div>