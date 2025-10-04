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
        <!-- Flex container for side by side cards -->
        <div class="flex flex-col md:flex-row gap-4 items-center justify-center">
            <div class="bg-gray-100 rounded-xl p-4 sm:p-6 shadow-lg w-full max-w-md mx-auto md:mx-0">
                <h2 class="text-lg font-semibold mb-4 text-gray-800">Penggunaan energi</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 justify-items-center">
                    <!-- Sisa kWh -->
                    <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center justify-center text-center">
                        <div class="relative w-24 h-24 sm:w-32 sm:h-32 flex items-center justify-center">
                            <svg class="absolute inset-0 w-full h-full transform -rotate-90">
                                <circle cx="50%" cy="50%" r="45%" stroke="#e5e7eb" stroke-width="10" fill="none" />
                                <circle cx="50%" cy="50%" r="45%" stroke="#facc15" stroke-width="10" fill="none"
                                    stroke-linecap="round" stroke-dasharray="377" stroke-dashoffset="93" />
                            </svg>
                            <div class="flex flex-col items-center">
                                <img src="{{ asset('images/icon_kWh.png') }}" alt="icon meter"
                                    class="w-8 h-8 sm:w-10 sm:h-10">
                                <p class="text-xl font-bold text-gray-800">16.82</p>
                                <p class="text-sm text-gray-500">kWh</p>
                            </div>
                        </div>
                        <p class="text-gray-700 font-medium mt-2">Sisa kWh</p>
                        <a href="#" class="text-xs text-gray-400 underline">Riwayat</a>
                    </div>
                </div>
            </div>
            <div class="bg-gray-100 rounded-xl p-4 sm:p-6 shadow-lg w-full max-w-md mx-auto md:mx-0">
                <h2 class="text-lg font-semibold mb-4 text-gray-800">Pilih pembayaran</h2>
                <!-- Changed grid from 6 to 2 columns -->
                <div class="grid grid-cols-2 gap-4">
                    <!-- Payment Option Rp20.000 -->
                    <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center">
                        <p class="text-xl font-bold text-gray-800">Rp20.000</p>
                        <p class="text-sm text-gray-500 mt-2">≈ 7.3 kWh</p>
                    </div>
                    <!-- Payment Option Rp50.000 -->
                    <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center">
                        <p class="text-xl font-bold text-gray-800">Rp50.000</p>
                        <p class="text-sm text-gray-500 mt-2">≈ 18.3 kWh</p>
                    </div>
                    <!-- Payment Option Rp100.000 -->
                    <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center">
                        <p class="text-xl font-bold text-gray-800">Rp100.000</p>
                        <p class="text-sm text-gray-500 mt-2">≈ 36.6 kWh</p>
                    </div>
                    <!-- Payment Option Rp200.000 -->
                    <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center">
                        <p class="text-xl font-bold text-gray-800">Rp200.000</p>
                        <p class="text-sm text-gray-500 mt-2">≈ 73.2 kWh</p>
                    </div>
                    <!-- Payment Option Rp500.000 -->
                    <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center">
                        <p class="text-xl font-bold text-gray-800">Rp500.000</p>
                        <p class="text-sm text-gray-500 mt-2">≈ 183.2 kWh</p>
                    </div>
                    <!-- Payment Option Rp1.000.000 -->
                    <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center">
                        <p class="text-xl font-bold text-gray-800">Rp1.000.000</p>
                        <p class="text-sm text-gray-500 mt-2">≈ 366.5 kWh</p>
                    </div>
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
