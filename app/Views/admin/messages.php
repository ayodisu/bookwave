<?php if (isset($message) && $message): ?>
    <div class="message <?php echo $message['type']; ?>">
        <span><?php echo e($message['text']); ?></span>
        <i class="fas fa-times" onclick="this.parentElement.remove();" style="cursor: pointer;"></i>
    </div>
<?php endif; ?>

<div class="admin-card">
    <div class="card-header">
        <h3 class="card-title">Messages</h3>
        <div style="font-size: 0.875rem; color: #64748b;">
            Total: <strong><?php echo count($messages); ?></strong>
        </div>
    </div>

    <div class="table-container">
        <?php if (!empty($messages)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 25%;">Sender</th>
                        <th style="width: 55%;">Message</th>
                        <th style="width: 10%;">ID</th>
                        <th style="width: 10%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $msg): ?>
                        <tr>
                            <td>
                                <div style="font-weight: 500; font-size: 0.875rem; color: var(--dark);"><?php echo e($msg['name']); ?></div>
                                <div style="font-size: 0.75rem; color: #64748b; margin-top: 2px;"><?php echo e($msg['email']); ?></div>
                                <div style="font-size: 0.75rem; color: #64748b; margin-top: 1px;"><i class="fas fa-phone-alt" style="font-size: 0.7rem; margin-right: 3px;"></i> <?php echo e($msg['number']); ?></div>
                            </td>
                            <td>
                                <div style="font-size: 0.875rem; line-height: 1.5; color: #334155; background: #f8fafc; padding: 0.5rem 0.75rem; border-radius: 4px;">
                                    "<?php echo e($msg['message']); ?>"
                                </div>
                            </td>
                            <td style="color: #64748b;">#<?php echo $msg['id']; ?></td>
                            <td>
                                <form action="<?php echo url('admin/messages'); ?>" method="post">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="message_id" value="<?php echo $msg['id']; ?>">
                                    <button type="submit" name="delete_message" class="action-btn delete" onclick="return confirm('Delete this message?');" title="Delete Message">
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
                <i class="fas fa-envelope-open-text" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                <p>No messages found.</p>
            </div>
        <?php endif; ?>
    </div>
</div>