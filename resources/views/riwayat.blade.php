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
    <main class="container mx-auto mt-4 px-2 sm:px-4 mb-20">
        <!-- Table Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-4 border-b">
                <h2 class="text-xl font-bold text-gray-800">Riwayat Pembayaran</h2>
            </div>

            <!-- Table Container with Horizontal Scroll -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                No.</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                ID Pelanggan</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                ID Meteran</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Nama</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Waktu Pembayaran</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Total Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-sm">1.</td>
                            <td class="px-4 py-3 text-sm">12345678</td>
                            <td class="px-4 py-3 text-sm">87654321</td>
                            <td class="px-4 py-3 text-sm font-medium">John Doe</td>
                            <td class="px-4 py-3 text-sm">30 September 2025</td>
                            <td class="px-4 py-3 text-sm font-semibold text-green-600">Rp500.000</td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-sm">2.</td>
                            <td class="px-4 py-3 text-sm">23456789</td>
                            <td class="px-4 py-3 text-sm">98765432</td>
                            <td class="px-4 py-3 text-sm font-medium">Jane Smith</td>
                            <td class="px-4 py-3 text-sm">28 September 2025</td>
                            <td class="px-4 py-3 text-sm font-semibold text-green-600">Rp750.000</td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 text-sm">3.</td>
                            <td class="px-4 py-3 text-sm">34567890</td>
                            <td class="px-4 py-3 text-sm">11223344</td>
                            <td class="px-4 py-3 text-sm font-medium">Ahmad Rizki</td>
                            <td class="px-4 py-3 text-sm">25 September 2025</td>
                            <td class="px-4 py-3 text-sm font-semibold text-green-600">Rp320.000</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-4 py-3 bg-gray-50 border-t flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    Menampilkan <span class="font-semibold">1-3</span> dari <span class="font-semibold">3</span> data
                </div>
                <div class="flex gap-1">
                    <button
                        class="px-3 py-1 text-sm border border-gray-300 rounded bg-white text-gray-400 cursor-not-allowed"
                        disabled>
                        Sebelumnya
                    </button>
                    <button
                        class="px-3 py-1 text-sm border border-yellow-500 rounded bg-yellow-500 text-white font-semibold">
                        1
                    </button>
                    <button
                        class="px-3 py-1 text-sm border border-gray-300 rounded bg-white text-gray-700 hover:bg-gray-50">
                        2
                    </button>
                    <button
                        class="px-3 py-1 text-sm border border-gray-300 rounded bg-white text-gray-700 hover:bg-gray-50">
                        Selanjutnya
                    </button>
                </div>
            </div>
        </div>
    </main>
    <!-- Mobile Bottom Navigation -->
    <x-bottom-navigation />
    {{-- <footer class="mt-10 py-6 bg-white text-center shadow-md">
        <p class="text-gray-600">&copy; 2025 Brand. All rights reserved.</p>
    </footer> --}}
</body>

</html>
