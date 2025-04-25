<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Website</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

    <!-- Navbar -->
    <nav class="bg-blue-500 shadow-lg">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="relative flex items-center justify-between h-16">
                <div class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start">
                    <div class="flex-shrink-0 text-white text-2xl">My Website</div>
                    <div class="hidden sm:block sm:ml-6">
                        <div class="flex space-x-4">
                            <!-- Admin specific links -->
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <a href="/admin/home" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium">Admin Home</a>
                                <a href="/admin/about" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium">Admin About</a>
                                <a href="/admin/contact" class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium">Admin Contact</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main content -->
    <div class="min-h-screen bg-gray-50 p-6">
        <h1 class="text-4xl text-center text-blue-600">Welcome to My Website</h1>
        <!-- Content will be injected here -->
    </div>

</body>
</html>

<script>
    const menuButton = document.querySelector('button[aria-controls="mobile-menu"]');
    const mobileMenu = document.getElementById('mobile-menu');

    menuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
</script>
