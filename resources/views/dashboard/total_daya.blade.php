<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total daya</title>
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

<body class="bg-[#000000] text-gray-900 font-sans">

    <x-header />
    <div class="bg-[#E1DFEC] mx-auto px-2 sm:px-4">
        <x-tab-navigation-home />
        <div class="container mx-auto rounded-3xl my-2">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Detail listrik pelanggan</h2>
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl p-4 shadow-lg">
                    <p class="text-sm text-gray-600 mb-1">Daya</p>
                    <div class="flex items-baseline gap-1">
                        <p class="text-base font-semibold"><span
                                class="text-2xl font-bold text-gray-900 total-power">0</span>W</p>
                        <span class="text-base font-normal">/</span>
                        <p class="text-base font-semibold"><span class="text-2xl font-bold text-gray-900">1300</span>W
                        </p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-4 shadow-lg">
                    <p class="text-sm text-gray-600 mb-1">Tegangan/Arus</p>
                    <div class="flex items-baseline gap-1">
                        <p class="text-base font-semibold"><span
                                class="text-2xl font-bold text-gray-900 total-volt">0</span>V</p>
                        <span class="text-base font-normal">/</span>
                        <p class="text-base font-semibold"><span
                                class="text-2xl font-bold text-gray-900 total-amp">0</span>A</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Gabungkan grafik 24 jam dan 7 hari dalam 1 flex row (setara) -->
        <div class="container mx-auto flex flex-col lg:flex-row gap-4 py-2">
            <!-- Left: Data Harian (24 Jam) -->
            <div class="flex flex-col flex-1 gap-4">
                <div class="bg-white rounded-3xl shadow-lg flex flex-col p-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center">Grafik Penggunaan Daya (24 Jam)</h3>
                    </div>
                    <div class="w-full h-[200px] mb-6">
                        <canvas id="hourly-chart"></canvas>
                        <p class="text-sm text-gray-600 flex items-center mt-1">
                            Terakhir update: <span class="">12 Oktober 2025</span>
                            <span class="ml-1">03.30</span>
                        </p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-white rounded-2xl shadow-md p-4">
                        <p class="text-xs text-gray-600 mb-1">Total kWh</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($weeklyTotalKwh, 2) }}</p>
                        <p class="text-xs text-gray-500 mt-1">7 hari</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-md p-4">
                        <p class="text-xs text-gray-600 mb-1">Total Biaya</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ number_format($weeklyTotalCost, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-1">7 hari</p>
                    </div>
                </div>
                <div class="bg-white rounded-3xl shadow-lg p-4 flex-1 flex flex-col">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Detail Penggunaan Per Jam</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-gray-200">
                                    <th class="text-left py-3 px-4 font-bold text-gray-700">Waktu</th>
                                    <th class="text-right py-3 px-4 font-bold text-gray-700">Daya (W)</th>
                                    <th class="text-right py-3 px-4 font-bold text-gray-700">kWh</th>
                                    <th class="text-right py-3 px-4 font-bold text-gray-700">Biaya (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hourlyData as $data)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                        <td class="py-3 px-4 text-gray-900">{{ $data['time'] }}</td>
                                        <td class="py-3 px-4 text-right text-gray-900 font-semibold">
                                            {{ $data['watt'] }}</td>
                                        <td class="py-3 px-4 text-right text-gray-900">
                                            {{ number_format($data['kwh'], 2) }}</td>
                                        <td class="py-3 px-4 text-right text-gray-900">
                                            {{ number_format($data['cost'], 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr class="bg-gray-100 font-bold border-t-2 border-gray-300">
                                    <td class="py-4 px-4 text-gray-900">Total</td>
                                    <td class="py-4 px-4 text-right text-gray-900">-</td>
                                    <td class="py-4 px-4 text-right text-gray-900">{{ number_format($totalKwh, 2) }}
                                    </td>
                                    <td class="py-4 px-4 text-right text-gray-900">
                                        {{ number_format($totalCost, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Right: Grafik 7 Hari (sejajar dengan grafik 24 jam) -->
            <!-- Right: Grafik 7 Hari (sejajar dengan grafik 24 jam) -->
            <div class="flex flex-col flex-1 gap-4">
                <div class="bg-white rounded-3xl shadow-lg p-4 flex flex-col h-[405px]">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Grafik Penggunaan 7 Hari Terakhir</h3>
                    <div class="w-full h-[240px] flex-1">
                        <canvas id="weekly-chart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-lg p-4 flex flex-col flex-1">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Rincian 7 Hari Terakhir</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-gray-200">
                                    <th class="text-left py-3 px-4 font-bold text-gray-700">Tanggal</th>
                                    <th class="text-right py-3 px-4 font-bold text-gray-700">Rata-rata (W)</th>
                                    <th class="text-right py-3 px-4 font-bold text-gray-700">Total kWh</th>
                                    <th class="text-right py-3 px-4 font-bold text-gray-700">Biaya (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($weeklyData as $day)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                        <td class="py-3 px-4 text-gray-900">{{ $day['date'] }}</td>
                                        <td class="py-3 px-4 text-right text-gray-900 font-semibold">{{ number_format($day['avg_watt'], 2) }}</td>
                                        <td class="py-3 px-4 text-right text-gray-900">{{ number_format($day['kwh'], 2) }}</td>
                                        <td class="py-3 px-4 text-right text-gray-900">{{ number_format($day['cost'], 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 text-center text-gray-500">Belum ada data mingguan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        async function fetchRealtimePower() {
            const res = await fetch('/api/realtime');
            const data = await res.json();

            const voltage = Math.round(data?.voltage || 0);
            document.querySelector('.total-volt').textContent = `${voltage}`;

            const amperage = data?.amperage;
            document.querySelector('.total-amp').textContent = `${amperage}`;

            const watt = Math.round(data?.watt || 0);
            document.querySelector('.total-power').textContent = `${watt}`;

            // Hitung progress
            const maxPower = 1300;
            const percentage = Math.min((watt / maxPower) * 100, 100);
        }

        // Jalankan realtime tiap 5 detik
        setInterval(fetchRealtimePower, 5000);
        fetchRealtimePower();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data dari Laravel Controller
        const hourlyChartLabels = @json($hourlyChartLabels);
        const hourlyChartData = @json($hourlyChartData);
        const weeklyChartLabels = @json($weeklyChartLabels);
        const weeklyChartKwh = @json($weeklyChartKwh);
        const weeklyChartCost = @json($weeklyChartCost);

        // Chart 24 Jam
        const hourlyCtx = document.getElementById('hourly-chart').getContext('2d');
        new Chart(hourlyCtx, {
            type: 'line',
            data: {
                labels: hourlyChartLabels,
                datasets: [{
                    label: 'Daya (W)',
                    data: hourlyChartData,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#3b82f6',
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Watt'
                        },
                        ticks: {
                            callback: function(value) {
                                return value + ' W';
                            }
                        }
                    }
                }
            }
        });

        // Chart 7 Hari
        const weeklyCtx = document.getElementById('weekly-chart').getContext('2d');
        new Chart(weeklyCtx, {
            type: 'line',
            data: {
                labels: weeklyChartLabels,
                datasets: [{
                        label: 'Konsumsi (kWh)',
                        data: weeklyChartKwh,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        yAxisID: 'y',
                        pointRadius: 5,
                        pointBackgroundColor: '#3b82f6',
                        fill: true
                    },
                    {
                        label: 'Biaya (Rp)',
                        data: weeklyChartCost,
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        yAxisID: 'y1',
                        pointRadius: 5,
                        pointBackgroundColor: '#22c55e',
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'kWh'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Rupiah'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
    </script>

</body>

</html>
