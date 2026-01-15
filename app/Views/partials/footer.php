<footer class="footer">
    <section class="box-container">
        <div class="box">
            <h3>BookWave</h3>
            <p class="footer-about">A portfolio project showcasing modern e-commerce development with PHP, MySQL, and clean MVC architecture.</p>
            <div class="footer-socials">
                <a href="https://github.com/ayodisu" title="GitHub"><i class="fab fa-github"></i></a>
                <a href="https://www.linkedin.com/in/abdulwahabdisu/" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
                <a href="https://x.com/_ayodisu" title="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="https://wa.me/2347038558332" title="Whatsapp"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
        <div class="box">
            <h3>Quick Links</h3>
            <a href="<?php echo url('home'); ?>"><i class="fas fa-angle-right"></i> Home</a>
            <a href="<?php echo url('shop'); ?>"><i class="fas fa-angle-right"></i> Shop</a>
            <a href="<?php echo url('about'); ?>"><i class="fas fa-angle-right"></i> About</a>
            <a href="<?php echo url('contact'); ?>"><i class="fas fa-angle-right"></i> Contact</a>
        </div>
        <div class="box">
            <h3>Account</h3>
            <a href="<?php echo url('cart'); ?>"><i class="fas fa-angle-right"></i> Cart</a>
            <a href="<?php echo url('orders'); ?>"><i class="fas fa-angle-right"></i> Orders</a>
            <?php if (!is_logged_in()): ?>
                <a href="<?php echo url('login'); ?>"><i class="fas fa-angle-right"></i> Login</a>
                <a href="<?php echo url('register'); ?>"><i class="fas fa-angle-right"></i> Register</a>
            <?php endif; ?>
        </div>
        <div class="box">
            <h3>Hire Me</h3>
            <p><i class="fas fa-envelope"></i> disuabdulwahab@gmail.com</p>
            <p><i class="fas fa-map-marker-alt"></i> Abuja, Nigeria</p>
            <a href="<?php echo url('contact'); ?>" class="footer-cta">Start a Project</a>
        </div>
    </section>
    <p class="credit">
        Built with <span class="heart"><i class="fas fa-heart"></i></span> by <span>Abdulwahab</span>
        | &copy; <?php echo date('Y'); ?> BookWave
    </p>
</footer>