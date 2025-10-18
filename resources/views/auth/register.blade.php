<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - DMCRS USTP Balubal</title>
    <!-- ✅ TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#191970] min-h-screen flex items-center justify-center">

    <div class="w-full max-w-5xl mx-auto bg-white rounded-xl shadow-2xl flex flex-col md:flex-row overflow-hidden">

        <!-- Logo Section -->
        <div class="md:w-1/2 flex items-center justify-center bg-gradient-to-br from-blue-900 to-blue-300 p-10">
            <div class="flex flex-col items-center">
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
            <h1 class="text-3xl font-extrabold text-[#191970] mb-2 text-center">Register to DMCRS</h1>
            <p class="text-center text-gray-600 mb-8">Fill in the details to get started</p>

            <!-- Form Start -->
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                           class="mt-1 block w-full rounded-md border border-black px-3 py-2 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                           class="mt-1 block w-full rounded-md border border-black px-3 py-2 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                           class="mt-1 block w-full rounded-md border border-black px-3 py-2 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                           class="mt-1 block w-full rounded-md border border-black px-3 py-2 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Department -->
                <div>
                    <label for="department_id" class="block text-sm font-medium text-gray-700">Department</label>
                    <select id="department_id" name="department_id" required
                            class="mt-1 block w-full rounded-md border border-black px-3 py-2 shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200">
                        <option value="">Select Department</option>
                        @foreach(App\Models\Department::all() as $department)
                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 mt-6">
                    <button type="submit"
                        class="w-full bg-yellow-500 hover:bg-yellow-600 text-[#191970] font-semibold py-3 rounded-lg shadow-md transition">
                        REGISTER
                    </button>
                    <a href="{{ route('login') }}"
                        class="w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold py-3 rounded-lg shadow-md transition text-center">
                        BACK TO LOGIN
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
