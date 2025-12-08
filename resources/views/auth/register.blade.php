<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register - PowerGO</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Exo -->
    <link href="https://fonts.googleapis.com/css2?family=Exo:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #dde3ff, #eef2ff, #f8fafc);
            font-family: 'Exo', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center px-4">

    <div class="bg-white shadow-2xl rounded-3xl p-8 w-full max-w-md border border-gray-100">

        <!-- Logo -->
        <h1 class="text-4xl font-bold text-center mb-2 tracking-tight text-gray-800">
            Power<span class="font-extrabold text-green-600">GO</span>
        </h1>
        <p class="text-center text-gray-500 text-sm -mt-1 mb-6">
            Create a new customer account
        </p>

        <!-- FORM -->
        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- NAME -->
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Name</label>
                <div class="flex items-center bg-gray-50 border rounded-xl px-3">
                    <img src="https://img.icons8.com/ios-glyphs/30/6B7280/user--v1.png" class="w-5 h-5 opacity-60" />
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full bg-transparent p-3 focus:outline-none"
                           required>
                </div>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- EMAIL -->
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Email</label>
                <div class="flex items-center bg-gray-50 border rounded-xl px-3">
                    <img src="https://img.icons8.com/ios-glyphs/30/6B7280/new-post.png" class="w-5 h-5 opacity-60" />
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full bg-transparent p-3 focus:outline-none"
                           required>
                </div>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- DAYA VA -->
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Pilih Daya (VA)</label>
                <select name="daya_va"
                        class="w-full bg-gray-50 border rounded-xl p-3 focus:ring focus:ring-blue-200">
                    <option value="450">450 VA</option>
                    <option value="900">900 VA</option>
                    <option value="1300">1300 VA</option>
                    <option value="2200">2200 VA</option>
                </select>
                @error('daya_va')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- BILLING TYPE -->
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Jenis Pelanggan</label>
                <select name="billing_type"
                        class="w-full bg-gray-50 border rounded-xl p-3 focus:ring focus:ring-blue-200">
                    <option value="prabayar">Prabayar</option>
                    <option value="pascabayar">Pascabayar</option>
                </select>
                @error('billing_type')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- PASSWORD -->
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Password</label>
                <div class="flex items-center bg-gray-50 border rounded-xl px-3">
                    <img src="https://img.icons8.com/ios-glyphs/30/6B7280/lock--v1.png"
                         class="w-5 h-5 opacity-60" />
                    <input type="password" name="password"
                           class="w-full bg-transparent p-3 focus:outline-none"
                           required>
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- CONFIRM PASSWORD -->
            <div>
                <label class="block text-sm font-semibold mb-1 text-gray-700">Confirm Password</label>
                <div class="flex items-center bg-gray-50 border rounded-xl px-3">
                    <img src="https://img.icons8.com/ios-glyphs/30/6B7280/lock--v1.png"
                         class="w-5 h-5 opacity-60" />
                    <input type="password" name="password_confirmation"
                           class="w-full bg-transparent p-3 focus:outline-none"
                           required>
                </div>
            </div>

            <!-- SUBMIT BUTTON -->
            <button
                class="w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl text-lg font-bold shadow-md hover:shadow-xl transition-all">
                Create Account
            </button>

            <!-- LOGIN LINK -->
            <p class="text-center text-sm mt-1 text-gray-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-blue-600 font-semibold">Login</a>
            </p>
        </form>

    </div>

</body>

</html>
