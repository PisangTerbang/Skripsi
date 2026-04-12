<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login - Sistem Skripsi</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white shadow-2xl rounded-2xl p-8">

        <!-- Logo / Title -->
        <h1 class="text-center text-gray-800 text-2xl font-bold mb-1">
            Sistem Skripsi
        </h1>
        <p class="text-center text-sm text-gray-500 mb-6">
            Silakan login untuk melanjutkan
        </p>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 text-green-600 text-sm text-center">
                {{ session('status') }}
            </div>
        @endif

        <!-- Error -->
        @if ($errors->any())
            <div class="mb-4 text-red-600 text-sm text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label class="block text-sm text-gray-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-400 focus:outline-none">
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm text-gray-600 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-400 focus:outline-none">
            </div>

            <!-- Remember -->
            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="remember" class="rounded">
                    <span class="text-gray-600">Remember me</span>
                </label>

                <a href="{{ route('password.request') }}" class="text-indigo-600 hover:underline">
                    Lupa password?
                </a>
            </div>

            <!-- Button -->
            <button type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 transition font-medium">
                Login
            </button>
        </form>

    </div>

</body>

</html>
