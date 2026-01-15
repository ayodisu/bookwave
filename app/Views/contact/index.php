<div class="heading">
    <h3>Let's Work Together</h3>
    <p><a href="<?php echo url('home'); ?>">Home</a> / Contact</p>
</div>

<?php if (isset($message) && $message): ?>
    <div class="message <?php echo $message['type']; ?>">
        <span><?php echo e($message['text']); ?></span>
        <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
    </div>
<?php endif; ?>

<section class="developer-intro">
    <div class="intro-container">
        <div class="intro-content">
            <span class="intro-badge">👋 Hello there!</span>
            <h2>I'm a Full-Stack Developer</h2>
            <p>This BookWave project is a personal portfolio piece showcasing my skills in PHP, MySQL, MVC architecture, and modern UI/UX design.</p>
            <p>Looking to build a web application, e-commerce site, or custom software solution? I'd love to help bring your ideas to life!</p>
            <div class="intro-skills">
                <span class="skill-tag">PHP</span>
                <span class="skill-tag">MySQL</span>
                <span class="skill-tag">JavaScript</span>
                <span class="skill-tag">HTML/CSS</span>
                <span class="skill-tag">MVC Architecture</span>
                <span class="skill-tag">Responsive Design</span>
            </div>
        </div>
    </div>
</section>

<section class="contact-links-section">
    <h3 class="title">Get In Touch</h3>
    <p class="section-desc">Connect with me on social media or send me a direct message.</p>

    <div class="contact-links-container">
        <a href="https://wa.me/2347038558332" target="_blank" class="contact-link-card whatsapp">
            <div class="icon"><i class="fab fa-whatsapp"></i></div>
            <div class="text">
                <h3>WhatsApp</h3>
                <span>Chat with me</span>
            </div>
        </a>

        <a href="mailto:disuabdulwahab@gmail.com" class="contact-link-card email">
            <div class="icon"><i class="fas fa-envelope"></i></div>
            <div class="text">
                <h3>Email</h3>
                <span>disuabdulwahab@gmail.com</span>
            </div>
        </a>

        <a href="https://twitter.com/_ayodisu" target="_blank" class="contact-link-card twitter">
            <div class="icon"><i class="fab fa-twitter"></i></div>
            <div class="text">
                <h3>Twitter</h3>
                <span>Follow updates</span>
            </div>
        </a>

        <a href="https://linkedin.com/in/abdulwahabdisu" target="_blank" class="contact-link-card linkedin">
            <div class="icon"><i class="fab fa-linkedin-in"></i></div>
            <div class="text">
                <h3>LinkedIn</h3>
                <span>Connect professionally</span>
            </div>
        </a>
    </div>
</section>