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
                <p class="text-sm text-gray-600 mb-1">Sisa kWh</p>
                <p class="text-2xl font-bold text-gray-900">
                    {{ number_format($hourlyData->first()['remaining_kwh'] ?? 0, 2) }} <span class="text-base font-normal">kWh</span>
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
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Grafik Sisa kWh Harian (7 Hari Terakhir)</h3>
                    <div class="relative w-full h-[300px]">
                        <canvas id="daily-chart"></canvas>
                    </div>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-3xl shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Penggunaan Per Jam</h3>

                    <div class="overflow-x-auto">
                        <div class="flex justify-between items-center mb-4">
                            <a href="{{ route('dashboard.sisa_kwh', ['date' => $prevDate]) }}"
                                class="px-3 py-2 bg-gray-200 rounded-lg">
                                &lt; {{ \Carbon\Carbon::parse($prevDate)->translatedFormat('l') }}
                            </a>

                            <h2 class="font-bold text-lg">
                                {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d M Y') }}
                            </h2>

                            <a href="{{ route('dashboard.sisa_kwh', ['date' => $nextDate]) }}"
                                class="px-3 py-2 bg-gray-200 rounded-lg">
                                {{ \Carbon\Carbon::parse($nextDate)->translatedFormat('l') }} &gt;
                            </a>
                        </div>
                        <table class="w-full table-fixed">
                            <colgroup>
                              <col style="width:40%"/>   {{-- Waktu --}}
                              <col style="width:20%"/>   {{-- Sisa kWh --}}
                              <col style="width:20%"/>   {{-- kWh --}}
                              <col style="width:20%"/>   {{-- Biaya --}}
                            </colgroup>
                        
                            <thead>
                              <tr class="text-left text-gray-600 text-sm border-b">
                                <th class="p-3">Waktu</th>
                                <th class="p-3 text-right">Sisa kWh</th>
                                <th class="p-3 text-right">kWh</th>
                                <th class="p-3 text-right">Biaya</th>
                              </tr>
                            </thead>
                        
                            <tbody>
                              @forelse ($hourlyData as $row)
                                <tr class="border-b hover:bg-gray-50">
                                  <td class="p-3 align-middle">{{ $row['time'] }}</td>
                                  <td class="p-3 align-middle text-right">{{ number_format($row['remaining_kwh'], 2) }}</td>
                                  <td class="p-3 align-middle text-right">{{ number_format($row['kwh'], 2) }}</td>
                                  <td class="p-3 align-middle text-right">Rp {{ number_format($row['cost'], 0, ',', '.') }}</td>
                                </tr>
                              @empty
                                <tr>
                                  <td colspan="4" class="p-4 text-center text-gray-500">Tidak ada data untuk tanggal ini.</td>
                                </tr>
                              @endforelse
                            </tbody>
                        
                            <tfoot>
                              <tr class="bg-gray-100 font-semibold">
                                <td class="p-3">Total</td>
                                {{-- kosongkan cell kedua supaya label Total tetap kiri --}}
                                <td class="p-3"></td>
                                <td class="p-3 text-right">{{ number_format($totalKwh ?? 0, 2) }}</td>
                                <td class="p-3 text-right">Rp {{ number_format($totalCost ?? 0, 0, ',', '.') }}</td>
                              </tr>
                            </tfoot>
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
                                <th class="py-2 text-right">Sisa kWh</th>
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
                                    <td class="py-2 text-right font-semibold">{{ number_format($w['remaining_kwh'], 2) }}</td>
                                    <td class="py-2 text-right">{{ number_format($w['kwh'], 2) }}</td>
                                    <td class="py-2 text-right">{{ number_format($w['cost'], 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-4 text-center text-gray-500">
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
        const dailyLabel = @json($dailyChartLabels ?? []);
        const dailyData = @json($dailyChartData ?? []);

        const ctx = document.getElementById('daily-chart').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dailyLabel,
                datasets: [{
                    label: 'Sisa kWh',
                    data: dailyData,
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
                            callback: v => v + ' kWh'
                        }
                    }
                }
            }
        });
    </script>

</body>

</html>
