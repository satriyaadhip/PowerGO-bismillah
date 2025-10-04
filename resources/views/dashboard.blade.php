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

<body class="bg-[#E1DFEC] text-gray-900 font-sans">
    <x-header />
    <main class="container mx-auto mt-4 px-2 sm:px-4">
        <div class="flex flex-col md:flex-row gap-4 justify-center items-stretch">
            <!-- Left Column -->
            <div class="bg-gray-100 rounded-xl p-4 sm:p-6 shadow-lg w-full max-w-md mx-auto md:mx-0">
                <h2 class="text-lg font-semibold mb-4 text-gray-800">Penggunaan energi</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Sisa kWh -->
                    <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center justify-center">
                        <div class="relative w-24 h-24 sm:w-32 sm:h-32 flex items-center justify-center">
                            <svg class="absolute inset-0 w-full h-full transform -rotate-90">
                                <circle cx="50%" cy="50%" r="45%" stroke="#e5e7eb" stroke-width="10" fill="none" />
                                <circle cx="50%" cy="50%" r="45%" stroke="#facc15" stroke-width="10"
                                    fill="none" stroke-linecap="round" stroke-dasharray="377" stroke-dashoffset="93" />
                            </svg>
                            <div class="flex flex-col items-center">
                                <img src="{{ asset('images/icon_kWh.png') }}" alt="icon meter" class="w-8 h-8 sm:w-10 sm:h-10">
                                <p class="text-xl font-bold text-gray-800">16.82</p>
                                <p class="text-sm text-gray-500">kWh</p>
                            </div>
                        </div>
                        <p class="text-gray-700 font-medium mt-2">Sisa kWh</p>
                        <a href="#" class="text-xs text-gray-400 underline">Riwayat</a>
                    </div>
                    <!-- Total daya -->
                    <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center justify-center">
                        <div class="relative w-24 h-24 sm:w-32 sm:h-32 flex items-center justify-center">
                            <svg class="absolute inset-0 w-full h-full transform -rotate-90">
                                <circle cx="50%" cy="50%" r="45%" stroke="#e5e7eb" stroke-width="10" fill="none" />
                                <circle cx="50%" cy="50%" r="45%" stroke="#22c55e" stroke-width="10"
                                    fill="none" stroke-linecap="round" stroke-dasharray="377" stroke-dashoffset="333" />
                            </svg>
                            <div class="flex flex-col items-center">
                                <img src="{{ asset('images/icon_watt.png') }}" alt="icon meter" class="w-6 h-6 sm:w-10 sm:h-10">
                                <p class="text-xl font-bold text-gray-800">97.92</p>
                                <p class="text-sm text-gray-500">/<span class="user_maxDaya"> 1300</span>W</p>
                            </div>
                        </div>
                        <p class="text-gray-700 font-medium mt-2">Total daya</p>
                        <a href="#" class="text-xs text-gray-400 underline">Detail</a>
                    </div>
                </div>
            </div>
            <!-- Right Column -->
            <div class="bg-gray-100 rounded-xl p-4 sm:p-6 shadow-lg w-full max-w-md mx-auto md:mx-0 flex flex-col gap-4">
                <h2 class="text-lg font-semibold text-gray-800">Penggunaan energi</h2>
                <div class="bg-white rounded-2xl shadow p-4 sm:p-5 w-full flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Terakhir pengisian daya</p>
                        <p class="text-2xl font-semibold text-gray-800 mt-1">22/12/2022</p>
                    </div>
                    <img src="{{ asset('images/meter.png') }}" alt="icon meter" class="w-6 h-6 sm:w-10 sm:h-10">
                </div>
                <div class="bg-white rounded-2xl shadow p-4 sm:p-5 w-full flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-500 font-medium">Rata-rata penggunaan kWh</p>
                        <p class="text-2xl font-semibold text-gray-800 mt-1">30 Wh/jam</p>
                    </div>
                    <img src="{{ asset('images/bolt.png') }}" alt="icon listrik" class="w-6 h-6 sm:w-10 sm:h-10">
                </div>
            </div>
        </div>
    </main>
    <!-- Mobile Bottom Navigation -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t shadow-lg sm:hidden z-50">
        <ul class="flex justify-around items-center py-2">
            <li>
                <a href="/Dashboard"
                    class="flex flex-col items-center text-xs {{ request()->is('Dashboard') ? 'font-bold text-yellow-500' : 'text-gray-500' }}">
                    <img src="{{ asset('images/button_home.png') }}" alt="Dashboard" class="w-6 h-6 mb-1">
                    Dashboard
                </a>
            </li>
            <li>
                <a href="/Pembayaran"
                    class="flex flex-col items-center text-xs {{ request()->is('Pembayaran') ? 'font-bold text-yellow-500' : 'text-gray-500' }}">
                    <img src="{{ asset('images/button_pembayaran.png') }}" alt="Pembayaran" class="w-6 h-6 mb-1">
                    Pembayaran
                </a>
            </li>
            <li>
                <a href="/Riwayat"
                    class="flex flex-col items-center text-xs {{ request()->is('Riwayat') ? 'font-bold text-yellow-500' : 'text-gray-500' }}">
                    <img src="{{ asset('images/button_transaksi.png') }}" alt="Riwayat" class="w-6 h-6 mb-1">
                    Riwayat
                </a>
            </li>
        </ul>
    </nav>
    {{-- <footer class="mt-10 py-6 bg-white text-center shadow-md">
        <p class="text-gray-600">&copy; 2025 Brand. All rights reserved.</p>
    </footer> --}}
</body>

</html>