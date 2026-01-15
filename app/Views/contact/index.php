<div class="heading">
    <h3>Contact Us</h3>
    <p><a href="<?php echo url('home'); ?>">Home</a> / Contact</p>
</div>

<?php if (isset($message) && $message): ?>
    <div class="message <?php echo $message['type']; ?>">
        <span><?php echo e($message['text']); ?></span>
        <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
    </div>
<?php endif; ?>

<section class="contact">
    <form action="<?php echo url('contact'); ?>" method="post" class="contact-form">
        <?php echo csrf_field(); ?>
        <h3>Say Something!</h3>
        <input type="text" name="name" required placeholder="Enter your name" class="box" value="<?php echo e($_SESSION['user_name'] ?? ''); ?>">
        <input type="email" name="email" required placeholder="Enter your email" class="box" value="<?php echo e($_SESSION['user_email'] ?? ''); ?>">
        <input type="tel" name="number" required placeholder="Enter your phone number" class="box">
        <textarea name="message" class="box" placeholder="Enter your message" cols="30" rows="10" required></textarea>
        <button type="submit" class="btn submit-btn">
            <span class="btn-text">Send Message</span>
            <span class="btn-loading" style="display:none;"><i class="fas fa-spinner fa-spin"></i> Sending...</span>
        </button>
    </form>
</section>

<script>
    document.querySelector('.contact-form').addEventListener('submit', function() {
        const btn = this.querySelector('.submit-btn');
        btn.disabled = true;
        btn.querySelector('.btn-text').style.display = 'none';
        btn.querySelector('.btn-loading').style.display = 'inline';
    });
</script>