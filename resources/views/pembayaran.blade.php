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
    <div class="container mx-auto mb-4 p-4 bg-[#D5DBEA] rounded-3xl">
        <!-- Flex container for side by side cards -->
        <div class="bg-[#eaeff4] rounded-3xl p-4">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Detail listrik pelangan</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-2">
                <div class="bg-white rounded-2xl p-4 shadow-sm">
                    <p class="text-sm text-gray-600 mb-1">Sisa kWh</p>
                    <p class="text-2xl font-bold text-gray-900">9.82 <span class="text-base font-normal">kWh</span></p>
                </div>
                <div class="bg-white rounded-2xl p-4 shadow-sm">
                    <p class="text-sm text-gray-600 mb-1">ID Pelanggan</p>
                    <p class="text-2xl font-bold text-gray-900">123876544</p>
                </div>
                <div class="bg-white rounded-2xl p-4 shadow-sm">
                    <p class="text-sm text-gray-600 mb-1">Nama pelanggan</p>
                    <p class="text-2xl font-bold text-gray-900">John Doe</p>
                </div>
                <div class="bg-white rounded-2xl p-4 shadow-sm">
                    <p class="text-sm text-gray-600 mb-1">Jenis Pelanggan</p>
                    <p class="text-xl font-bold text-gray-900">1300 VA - Prabayar</p>
                </div>
                <div class="bg-white rounded-2xl p-4 shadow-sm">
                    <p class="text-sm text-gray-600 mb-1">Isi Terakhir</p>
                    <p class="text-xl font-bold text-gray-900">22 Januari 2025</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container mx-auto p-4 bg-[#D5DBEA] rounded-3xl">
        <!-- Pembayaran Section -->
        <div class="flex flex-col gap-4">
            <div class="bg-[#eaeff4] rounded-3xl p-4">
                <h2 class="text-xl font-bold text-gray-900">
                    Pembayaran listrik <span class="text-purple-600">Prabayar</span>
                </h2>
                <p class="text-gray-700 mb-2">Pilih salah satu daya yang cocok buatmu di bawah ini.</p>

                <!-- Nominal Options -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2">
                    <button class="bg-gradient-to-br from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white rounded-2xl p-4 text-left transition-all shadow-md hover:shadow-lg">
                        <p class="text-lg font-bold">Rp20.000</p>
                        <p class="text-sm opacity-90">13.63 kWh</p>
                    </button>
                    <button class="bg-gradient-to-br from-teal-400 to-teal-500 hover:from-teal-500 hover:to-teal-600 text-white rounded-2xl p-4 text-left transition-all shadow-md hover:shadow-lg">
                        <p class="text-lg font-bold">Rp50.000</p>
                        <p class="text-sm opacity-90">34.09 kWh</p>
                    </button>
                    <button class="bg-gradient-to-br from-cyan-400 to-cyan-500 hover:from-cyan-500 hover:to-cyan-600 text-white rounded-2xl p-4 text-left transition-all shadow-md hover:shadow-lg">
                        <p class="text-lg font-bold">Rp100.000</p>
                        <p class="text-sm opacity-90">68.18 kWh</p>
                    </button>
                    <button class="bg-gradient-to-br from-cyan-500 to-cyan-600 hover:from-cyan-600 hover:to-cyan-700 text-white rounded-2xl p-4 text-left transition-all shadow-md hover:shadow-lg">
                        <p class="text-lg font-bold">Rp200.000</p>
                        <p class="text-sm opacity-90">136.36 kWh</p>
                    </button>
                    <button class="bg-gradient-to-br from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white rounded-2xl p-4 text-left transition-all shadow-md hover:shadow-lg">
                        <p class="text-lg font-bold">Rp500.000</p>
                        <p class="text-sm opacity-90">340.45 kWh</p>
                    </button>
                    <button class="bg-gradient-to-br from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 text-white rounded-2xl p-4 text-left transition-all shadow-md hover:shadow-lg">
                        <p class="text-lg font-bold">Rp1.000.000</p>
                        <p class="text-sm opacity-90">680.90 kWh</p>
                    </button>
                </div>
            </div>
            <div class="bg-[#eaeff4] rounded-3xl p-4">
                <!-- Selected Amount & Payment Methods -->
                <div class="grid grid-cols-1 lg:grid-cols-2 bg-white rounded-2xl overflow-hidden">
                    <!-- Daya Terpilih -->
                    <div class="bg-gradient-to-br from-pink-500 to-pink-600 p-6 text-white rounded-tl-2xl rounded-tr-2xl lg:rounded-tr-none">
                        <p class="text-xl font-bold mb-4">Daya terpilih</p>
                        <h2 class="text-4xl font-bold mb-2">Rp1.000.000</h2>
                        <p class="text-lg">680.90 kWh</p>
                    </div>

                    <!-- Payment Methods -->
                    <div class="bg-white rounded-2xl p-4">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Pembayaran menggunakan</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            <button class="bg-[#9db33f] hover:bg-[#8da035] text-white rounded-2xl px-6 py-4 font-semibold transition-colors shadow-md">
                                QRIS
                            </button>
                            <button class="bg-[#0da5a5] hover:bg-[#0c9393] text-white rounded-2xl px-6 py-4 font-semibold transition-colors shadow-md">
                                m-Bank
                            </button>
                            <button class="bg-[#1a8bc9] hover:bg-[#1679b3] text-white rounded-2xl px-6 py-4 font-semibold transition-colors shadow-md">
                                GoPay
                            </button>
                            <button class="bg-[#ff6b1a] hover:bg-[#e65f17] text-white rounded-2xl px-6 py-4 font-semibold transition-colors shadow-md">
                                ShopeePay
                            </button>
                            <button class="bg-[#6b4ab5] hover:bg-[#5e40a0] text-white rounded-2xl px-6 py-4 font-semibold transition-colors shadow-md">
                                Kredit/debit
                            </button>
                            <button class="bg-[#3fb562] hover:bg-[#36a055] text-white rounded-2xl px-6 py-4 font-semibold transition-colors shadow-md">
                                Virtual Account
                            </button>
                            <button class="bg-[#c94444] hover:bg-[#b33d3d] text-white rounded-2xl px-6 py-4 font-semibold transition-colors shadow-md">
                                Alfamart
                            </button>
                            <button class="bg-[#4a9fd8] hover:bg-[#428ec4] text-white rounded-2xl px-6 py-4 font-semibold transition-colors shadow-md">
                                Indomaret
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lanjut Pembayaran Button -->
        <div class="flex justify-center">
            <button class="bg-white hover:bg-gray-50 transition-colors rounded-full p-4 flex items-center gap-3 font-bold text-gray-900 mt-4 shadow-md hover:shadow-xl">
                Lanjut pembayaran
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-6 h-6">
                    <circle cx="12" cy="12" r="12" fill="#000000" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" stroke="#FFFFFF" d="M10 7.5l5 4.5-5 4.5" />
                </svg>
            </button>
        </div>
    </div>
    <!-- Mobile Bottom Navigation -->
    <x-bottom-navigation />
    {{-- <footer class="mt-10 py-6 bg-white text-center shadow-md">
        <p class="text-gray-600">&copy; 2025 Brand. All rights reserved.</p>
    </footer> --}}
</body>

</html>