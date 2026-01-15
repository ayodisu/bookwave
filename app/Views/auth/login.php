<!-- Modal Popup for Errors -->
<?php if (!empty($errors)): ?>
    <div id="messageModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" style="background: rgba(0,0,0,0.5);">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all animate-[modalSlide_0.3s_ease-out]">
            <div class="p-6 text-center">
                <div class="mx-auto w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mb-4">
                    <i class="fas fa-exclamation-circle text-red-500 text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Login Failed</h3>
                <?php foreach ($errors as $error): ?>
                    <p class="text-gray-600 text-lg"><?php echo e($error); ?></p>
                <?php endforeach; ?>
            </div>
            <div class="px-6 pb-6 text-center">
                <button onclick="closeModal()" class="px-8 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg transition-colors duration-200 shadow-lg hover:shadow-xl">
                    Try Again
                </button>
            </div>
        </div>
    </div>
    <style>
        @keyframes modalSlide {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
    </style>
    <script>
        function closeModal() {
            document.getElementById('messageModal').style.display = 'none';
        }
        document.getElementById('messageModal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeModal();
        });
    </script>
<?php endif; ?>

<div class="form-container">
    <form action="<?php echo url('login'); ?>" method="post" class="auth-form">
        <?php echo csrf_field(); ?>
        <h3>Login Now</h3>
        <input type="email" name="email" placeholder="Enter your email" required class="box">
        <input type="password" name="password" placeholder="Enter your password" required class="box">
        <button type="submit" class="btn submit-btn">
            <span class="btn-text">Login Now</span>
            <span class="btn-loading" style="display:none;"><i class="fas fa-spinner fa-spin"></i> Logging in...</span>
        </button>
        <p>Don't have an account? <a href="<?php echo url('register'); ?>">Register now</a></p>
    </form>
</div>

<script>
    document.querySelector('.auth-form').addEventListener('submit', function() {
        const btn = this.querySelector('.submit-btn');
        btn.disabled = true;
        btn.querySelector('.btn-text').style.display = 'none';
        btn.querySelector('.btn-loading').style.display = 'inline';
    });
</script>