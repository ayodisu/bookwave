<?php if (isset($message) && $message): ?>
    <div class="message <?php echo $message['type']; ?>">
        <span><?php echo e($message['text']); ?></span>
        <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
    </div>
<?php endif; ?>

<section class="users">
    <h1 class="title">All Users</h1>
    <div class="box-container">
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <div class="box">
                    <p>ID: <span><?php echo $user['id']; ?></span></p>
                    <p>Name: <span><?php echo e($user['name']); ?></span></p>
                    <p>Email: <span><?php echo e($user['email']); ?></span></p>
                    <p>Type: <span style="color: <?php echo $user['user_type'] == 'admin' ? 'green' : 'inherit'; ?>;">
                            <?php echo ucfirst(e($user['user_type'])); ?>
                        </span></p>

                    <?php if ($user['id'] !== $_SESSION['admin_id']): ?>
                        <form action="<?php echo url('admin/users'); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <button type="submit" name="delete_user" class="delete-btn" onclick="return confirm('Delete this user?');">Delete</button>
                        </form>
                    <?php else: ?>
                        <span class="option-btn" style="opacity:0.5;">Current User</span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty">No users found!</p>
        <?php endif; ?>
    </div>
</section>