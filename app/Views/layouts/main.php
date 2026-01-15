<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'BookWave'); ?> - BookWave</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo url('css/style.css'); ?>">
</head>

<body>

    <!-- Toast Notification -->
    <?php $toast = show_toast();
    if ($toast): ?>
        <div id="toast" class="fixed top-5 right-5 z-[99999] transform transition-all duration-500">
            <div class="flex items-center gap-3 px-6 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl shadow-2xl">
                <div class="flex-shrink-0 w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-xl"></i>
                </div>
                <div>
                    <p class="font-semibold text-lg"><?php echo $toast; ?></p>
                    <p class="text-green-100 text-sm">You are now logged in</p>
                </div>
                <button onclick="closeToast()" class="ml-4 text-white/80 hover:text-white">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <script>
            function closeToast() {
                const toast = document.getElementById('toast');
                toast.style.transform = 'translateX(150%)';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 500);
            }
            setTimeout(closeToast, 4000);
        </script>
    <?php endif; ?>

    <?php require_once '../app/Views/partials/header.php'; ?>

    <?php echo $content; ?>

    <?php require_once '../app/Views/partials/footer.php'; ?>

    <script src="<?php echo url('js/script.js'); ?>"></script>
</body>

</html>