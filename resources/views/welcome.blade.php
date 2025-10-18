<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USTP DMCRS</title>
    <!-- ✅ TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-800">

    <!-- Wrapper -->
    <div class="flex flex-col min-h-screen">

        <!-- Header -->
        <header class="bg-blue-900 text-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 sm:py-4 flex items-center">
                <img src="{{ asset('images/dmcrs-logo.jpg') }}" alt="USTP Logo" class="h-8 sm:h-10 mr-2 sm:mr-3 rounded-full">
                <span class="text-lg sm:text-xl font-bold tracking-wide">USTP Balubal Campus</span>
            </div>
        </header>

        <!-- Hero Section -->
        <main class="flex-grow flex items-center justify-center relative overflow-hidden">
            <!-- Subtle Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-gray-50 to-yellow-50"></div>

            <!-- Hero Content -->
            <div class="relative z-10 text-center px-4 sm:px-6 py-16 sm:py-20">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-blue-900 mb-4 leading-tight">
                    Digital Make-Up Class Request System
                </h1>
                <p class="text-base sm:text-lg md:text-xl text-gray-700 mb-6 sm:mb-8">
                    Efficient · Transparent · Paperless
                </p>
                <a href="{{ route('login') }}"
                   class="inline-block bg-yellow-400 hover:bg-yellow-500 text-blue-900 font-semibold px-6 sm:px-8 py-2 sm:py-3 rounded-lg shadow-lg transition text-sm sm:text-base">
                    Get Started
                </a>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-blue-900 text-white py-3 sm:py-4">
            <div class="max-w-7xl mx-auto text-center text-xs sm:text-sm px-4">
                © {{ date('Y') }} USTP Balubal Campus · Digital Make-Up Class Request System
            </div>
        </footer>

    </div>

</body>
</html>
