<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="text-center">
        <h1 class="text-9xl font-bold text-purple-600">404</h1>
        <h2 class="text-3xl font-semibold text-gray-800 mt-4">Page Not Found</h2>
        <p class="text-gray-600 mt-2">The page you're looking for doesn't exist.</p>
        <a href="<?php echo BASE_URL; ?>" class="inline-block mt-6 px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
            Go Home
        </a>
    </div>
</body>

</html>