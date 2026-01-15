<?php if (isset($message) && $message): ?>
    <div class="message <?php echo $message['type']; ?>">
        <span><?php echo e($message['text']); ?></span>
        <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
    </div>
<?php endif; ?>

<section class="messages">
    <h1 class="title">Contact Messages</h1>
    <div class="box-container">
        <?php if (!empty($messages)): ?>
            <?php foreach ($messages as $msg): ?>
                <div class="box">
                    <p>Name: <span><?php echo e($msg['name']); ?></span></p>
                    <p>Email: <span><?php echo e($msg['email']); ?></span></p>
                    <p>Number: <span><?php echo e($msg['number']); ?></span></p>
                    <p>Message: <span><?php echo e($msg['message']); ?></span></p>

                    <form action="<?php echo url('admin/messages'); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="message_id" value="<?php echo $msg['id']; ?>">
                        <button type="submit" name="delete_message" class="delete-btn" onclick="return confirm('Delete this message?');">Delete</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty">No messages yet!</p>
        <?php endif; ?>
    </div>
</section>