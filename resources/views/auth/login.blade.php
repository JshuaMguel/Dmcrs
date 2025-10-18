<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DMCRS USTP Balubal</title>
    <!-- ✅ TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#191970] min-h-screen flex items-center justify-center">

    <div class="w-full max-w-5xl mx-auto bg-white rounded-xl shadow-2xl flex flex-col md:flex-row overflow-hidden">

        <!-- Logo Section (Dark Blue → Light Blue Gradient) -->
        <div class="md:w-1/2 flex items-center justify-center bg-gradient-to-br from-blue-900 to-blue-300 p-10">
            <div class="flex flex-col items-center">
                <!-- Logo Circle -->
                <div class="bg-white p-4 rounded-full shadow-lg flex items-center justify-center">
                    <img src="{{ asset('images/dmcrs-logo.jpg') }}"
                         alt="DMCRS Logo"
                         class="h-40 w-40 object-contain rounded-full">
                </div>
                <h2 class="mt-6 text-2xl font-bold text-white text-center drop-shadow">

                </h2>
            </div>
        </div>

        <!-- Form Section -->
        <div class="md:w-1/2 p-10 flex flex-col justify-center">
            <h1 class="text-3xl font-extrabold text-[#191970] mb-2 text-center">Welcome to DMCRS</h1>
            <p class="text-center text-gray-600 mb-8">Sign in to access your account</p>

            <!-- Form Start -->
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" type="email" name="email" required autofocus
                           class="mt-1 block w-full rounded-md border border-black px-3 py-2 focus:border-yellow-500 focus:ring focus:ring-yellow-200">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" type="password" name="password" required
                           class="mt-1 block w-full rounded-md border border-black px-3 py-2 focus:border-yellow-500 focus:ring focus:ring-yellow-200">
                </div>

                <!-- ✅ Remember Me -->
                <div class="flex items-center">
                    <input id="remember" type="checkbox" name="remember"
                           class="h-4 w-4 text-yellow-500 border-gray-300 rounded focus:ring-yellow-400">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">
                        Remember Me
                    </label>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 mt-6">
                    <button type="submit"
                        class="w-full bg-yellow-500 hover:bg-yellow-600 text-[#191970] font-semibold py-3 rounded-lg shadow-md transition">
                        LOGIN
                    </button>
                    <a href="{{ route('register') }}"
                        class="w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 rounded-lg shadow-md transition text-center">
                        CREATE ACCOUNT
                    </a>
                </div>
            </form>
            <!-- Form End -->

            <footer class="mt-10 text-xs text-gray-500 text-center">
                © 2025 University of Science and Technology of Southern Philippines
            </footer>
        </div>
    </div>

</body>
</html>
