<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <!-- Exo font from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Exo:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Exo', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 text-gray-900 font-sans">
<header class="py-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="/"><h1 class="text-xl">Power<span class="font-bold">GO</span></h1></a>
        <nav>
            <ul class="flex">
                <li>
                    <a href="/Dashboard" class="flex items-center px-4 py-2 bg-white rounded-[30px] transition-all {{ request()->is('Dashboard') ? 'font-bold shadow-md' : 'hover:shadow-md' }}">
                        <img src="{{ asset('images/button_home.png') }}" alt="Dashboard" class="w-5 h-5 mr-2">
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="/Pembayaran" class="flex items-center px-4 py-2 bg-white rounded-[30px] transition-all {{ request()->is('Pembayaran') ? 'font-bold shadow-md' : 'hover:shadow-md' }}">
                        <img src="{{ asset('images/button_pembayaran.png') }}" alt="Pembayaran" class="w-5 h-5 mr-2">
                        <span>Pembayaran</span>
                    </a>
                </li>
                <li>
                    <a href="/Riwayat" class="flex items-center px-4 py-2 bg-white rounded-[30px] transition-all {{ request()->is('Riwayat') ? 'font-bold shadow-md' : 'hover:shadow-md' }}">
                        <img src="{{ asset('images/button_transaksi.png') }}" alt="Riwayat" class="w-5 h-5 mr-2">
                        <span>Riwayat</span>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="">profile</div>
        
    </div>
</header>
<main class="container mx-auto mt-10 px-6 text-center">
    <h2 class="text-3xl font-bold">Welcome to our homepage</h2>
    <p class="mt-4 text-lg text-gray-600">Discover more about us and get in touch.</p>
</main>
<footer class="mt-10 py-6 bg-white text-center shadow-md">
    <p class="text-gray-600">&copy; 2025 Brand. All rights reserved.</p>
</footer>
</body>
</html>