<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - PowerGO Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Load Exo font -->
    <link href="https://fonts.googleapis.com/css2?family=Exo:wght@400;600;800&display=swap" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #eef2ff, #f8fafc);
            font-family: 'Exo', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center">

    <div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-md">

        <!-- LOGO POWERGO -->
        <div class="flex justify-center mb-2">
            <h1 class="text-4xl font-medium tracking-tight">
                Power<span class="font-black">GO</span>
            </h1>
        </div>
        <p class="text-center text-gray-500 text-sm mb-6 -mt-2">
            Smart Electricity Dashboard
        </p>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-semibold mb-1">Email</label>
                <input type="email" name="email"
                       class="w-full border rounded-lg p-3 focus:ring focus:ring-blue-300"
                       required autofocus>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Password</label>
                <input type="password" name="password"
                       class="w-full border rounded-lg p-3 focus:ring focus:ring-blue-300"
                       required>
            </div>

            <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg text-lg font-semibold transition">
                Login
            </button>

            <p class="text-center text-sm mt-2">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-blue-600 font-semibold">Register</a>
            </p>
        </form>

    </div>
</body>
</html>
