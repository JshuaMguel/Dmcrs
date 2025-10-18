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
                    <div class="relative">
                        <input id="password" type="password" name="password" required
                               class="mt-1 block w-full rounded-md border border-black px-3 py-2 pr-10 focus:border-yellow-500 focus:ring focus:ring-yellow-200">
                        <!-- Show/Hide Password Toggle -->
                        <button type="button" onclick="togglePassword()"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-600 hover:text-gray-800">
                            <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <script>
                    function togglePassword() {
                        const passwordInput = document.getElementById('password');
                        const eyeIcon = document.getElementById('eye-icon');
                        
                        if (passwordInput.type === 'password') {
                            passwordInput.type = 'text';
                            // Change to "eye-slash" icon
                            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />';
                        } else {
                            passwordInput.type = 'password';
                            // Change back to "eye" icon
                            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />';
                        }
                    }
                </script>

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
