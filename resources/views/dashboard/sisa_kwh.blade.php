<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total Daya</title>

    <link href="https://fonts.googleapis.com/css2?family=Exo:wght@400;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Exo', 'sans-serif']
                    },
                }
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-[#E1DFEC] text-gray-900 font-sans">

    <x-header />
    <x-tab-navigation-home />

    <div class="container mx-auto px-4 py-4">

        <h2 class="text-xl font-bold text-gray-900 mb-4">Detail Listrik Pelanggan</h2>

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
            <div class="bg-white rounded-2xl p-4 shadow-sm">
                <p class="text-sm text-gray-600 mb-1">Total kWh (24 Jam)</p>
                <p class="text-2xl font-bold text-gray-900">
                    {{ number_format($totalKwh, 2) }} <span class="text-base font-normal">kWh</span>
                </p>
            </div>

            <div class="bg-white rounded-2xl p-4 shadow-sm">
                <p class="text-sm text-gray-600 mb-1">Total Biaya</p>
                <p class="text-2xl font-bold text-gray-900">
                    Rp {{ number_format($totalCost, 0, ',', '.') }}
                </p>
            </div>

            <div class="bg-white rounded-2xl p-4 shadow-sm">
                <p class="text-sm text-gray-600 mb-1">Rata-rata Watt</p>
                <p class="text-2xl font-bold text-gray-900">
                    {{ number_format($hourlyData->avg('watt') ?? 0, 0) }} W
                </p>
            </div>

            <div class="bg-white rounded-2xl p-4 shadow-sm">
                <p class="text-sm text-gray-600 mb-1">Rata-rata kWh</p>
                <p class="text-2xl font-bold text-gray-900">
                    {{ number_format($hourlyData->avg('kwh') ?? 0, 2) }} kWh
                </p>
            </div>
        </div>


        <div class="flex flex-col md:flex-row gap-4">

            <!-- Left Column = 24-Hour Data -->
            <div class="flex-1">

                <h3 class="text-xl font-bold text-gray-900 mb-4">Data Harian (24 Jam)</h3>

                <!-- Chart -->
                <div class="bg-white rounded-3xl shadow p-6 mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Grafik Penggunaan Listrik</h3>
                    <div class="relative w-full h-[300px]">
                        <canvas id="hourly-chart"></canvas>
                    </div>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-3xl shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Penggunaan Per Jam</h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b-2 border-gray-200">
                                    <th class="py-3 px-4 text-left">Waktu</th>
                                    <th class="py-3 px-4 text-right">Watt</th>
                                    <th class="py-3 px-4 text-right">kWh</th>
                                    <th class="py-3 px-4 text-right">Biaya (Rp)</th>
                                </tr>
                            </thead>

                            <tbody>

                                {{-- ganti loop lama yang menggunakan hourlyKwh --}}
                                @foreach ($hourlyData as $data)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                        <td class="py-3 px-4 text-gray-900">{{ $data['time'] }}</td>
                                        <td class="py-3 px-4 text-right text-gray-900 font-semibold">
                                            {{ number_format($data['remaining_kwh'], 2) }}</td>
                                        <td class="py-3 px-4 text-right text-gray-900">
                                            {{ number_format($data['kwh'], 2) }}</td>
                                        <td class="py-3 px-4 text-right text-gray-900">
                                            {{ number_format($data['cost'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach

                                <tr class="bg-gray-100 font-bold border-t-2 border-gray-300">
                                    <td class="py-3 px-4">Total</td>
                                    <td class="py-3 px-4 text-right">-</td>
                                    <td class="py-3 px-4 text-right">{{ number_format($totalKwh, 2) }}</td>
                                    <td class="py-3 px-4 text-right">{{ number_format($totalCost, 0, ',', '.') }}</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Right Column = Weekly Data -->
            <div class="w-full md:w-1/3">

                <h3 class="text-xl font-bold text-gray-900 mb-4">Data 7 Hari Terakhir</h3>

                <div class="bg-white rounded-3xl shadow p-5">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="py-2 text-left">Tanggal</th>
                                <th class="py-2 text-right">kWh</th>
                                <th class="py-2 text-right">Biaya</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($weeklyData as $w)
                                <tr class="border-b border-gray-100">
                                    <td class="py-2 text-gray-900">
                                        {{ $w['date'] }}
                                    </td>
                                    <td class="py-2 text-right">{{ $w['kwh'] }}</td>
                                    <td class="py-2 text-right">{{ $w['cost'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-4 text-center text-gray-500">
                                        Tidak ada data
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>


    <script>
        const hourlyLabel = @json($hourlyChartLabels);
        const hourlyData = @json($hourlyChartData);

        const ctx = document.getElementById('hourly-chart').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: hourlyLabel,
                datasets: [{
                    label: 'Watt',
                    data: hourlyData,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.15)',
                    tension: 0.35,
                    borderWidth: 3,
                    pointRadius: 3,
                    fill: true,
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: v => v + ' W'
                        }
                    }
                }
            }
        });
    </script>

</body>

</html>
