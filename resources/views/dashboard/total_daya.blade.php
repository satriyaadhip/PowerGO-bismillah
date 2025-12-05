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
                        sans: ['Exo', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>

<body class="bg-[#E1DFEC] text-gray-900 font-sans">

    <x-header />

    <div class="bg-[#E1DFEC] mx-auto px-2 sm:px-4">
        <x-tab-navigation-home />

        <!-- ========================= TOP SUMMARY ========================= -->
        <div class="container mx-auto rounded-3xl my-2">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Detail Listrik Pelanggan</h2>

            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Daya -->
                <div class="bg-white rounded-2xl p-4 shadow-lg">
                    <p class="text-sm text-gray-600 mb-1">Daya</p>
                    <div class="flex items-baseline gap-1">
                        <p class="text-base font-semibold">
                            <span class="text-2xl font-bold text-gray-900 total-power">0</span>W
                        </p>
                        <span class="text-base font-normal">/</span>
                        <p class="text-base font-semibold"><span class="text-2xl font-bold">1300</span>W</p>
                    </div>
                </div>

                <!-- Voltage / Ampere -->
                <div class="bg-white rounded-2xl p-4 shadow-lg">
                    <p class="text-sm text-gray-600 mb-1">Tegangan / Arus</p>
                    <div class="flex items-baseline gap-1">
                        <p class="text-base font-semibold">
                            <span class="text-2xl font-bold text-gray-900 total-volt">0</span>V
                        </p>
                        <span class="mx-1">/</span>
                        <p class="text-base font-semibold">
                            <span class="text-2xl font-bold text-gray-900 total-amp">0</span>A
                        </p>
                    </div>
                </div>

                <!-- Total kWh 7 hari -->
                <div class="bg-white rounded-2xl p-4 shadow-lg">
                    <p class="text-sm text-gray-600 mb-1">Total kWh</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($weeklyTotalKwh, 2) }}</p>
                    <p class="text-xs text-gray-500 mt-1">7 hari</p>
                </div>

                <!-- Total biaya 7 hari -->
                <div class="bg-white rounded-2xl p-4 shadow-lg">
                    <p class="text-sm text-gray-600 mb-1">Total Biaya</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $weeklyTotalCost }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">7 hari</p>
                </div>
            </div>
        </div>


        <!-- ========================= MAIN CHARTS ========================= -->
        <div class="container mx-auto flex flex-col lg:flex-row gap-4 py-2">

            <!-- LEFT SECTION — 24 HOURS -->
            <div class="flex flex-col flex-1 gap-4">

                <!-- 24 Hour Chart -->
                <div class="bg-white rounded-3xl shadow-lg p-4 flex flex-col">
                    <h3 class="text-lg font-bold text-gray-900">Grafik Penggunaan Daya (24 Jam)</h3>
                    <div class="w-full h-[250px] mb-4">
                        <canvas id="hourly-chart"></canvas>
                    </div>
                </div>

                <!-- Hourly Table -->
                <div class="bg-white rounded-3xl shadow-lg p-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Detail Penggunaan Per Jam</h3>

                    <div class="overflow-x-auto">
                        <div class="flex justify-between items-center mb-4">
                            <a href="{{ route('dashboard.total_daya', ['date' => $prevDate]) }}"
                                class="px-3 py-2 bg-gray-200 rounded-lg">
                                &lt; {{ \Carbon\Carbon::parse($prevDate)->translatedFormat('l') }}
                            </a>

                            <h2 class="font-bold text-lg">
                                {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d M Y') }}
                            </h2>

                            <a href="{{ route('dashboard.total_daya', ['date' => $nextDate]) }}"
                                class="px-3 py-2 bg-gray-200 rounded-lg">
                                {{ \Carbon\Carbon::parse($nextDate)->translatedFormat('l') }} &gt;
                            </a>
                        </div>
                        <table class="w-full table-fixed">
                            <colgroup>
                              <col style="width:40%"/>   {{-- Waktu --}}
                              <col style="width:20%"/>   {{-- Watt --}}
                              <col style="width:20%"/>   {{-- kWh --}}
                              <col style="width:20%"/>   {{-- Biaya --}}
                            </colgroup>
                        
                            <thead>
                              <tr class="text-left text-gray-600 text-sm border-b">
                                <th class="p-3">Waktu</th>
                                <th class="p-3">Watt</th>
                                <th class="p-3 text-right">kWh</th>
                                <th class="p-3 text-right">Biaya</th>
                              </tr>
                            </thead>
                        
                            <tbody>
                              @forelse ($hourlyData as $row)
                                <tr class="border-b hover:bg-gray-50">
                                  <td class="p-3 align-middle">{{ $row['time'] }}</td>
                                  <td class="p-3 align-middle">{{ $row['watt'] }} W</td>
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

                        <!-- <table class="w-full">
                        <thead>
                        <tr class="border-b-2 border-gray-200">
                            <th class="text-left py-3 px-4 font-bold text-gray-700">Waktu</th>
                            <th class="text-right py-3 px-4 font-bold text-gray-700">Daya (W)</th>
                            <th class="text-right py-3 px-4 font-bold text-gray-700">kWh</th>
                            <th class="text-right py-3 px-4 font-bold text-gray-700">Biaya</th>
                        </tr>
                        </thead>

                        <tbody>
                        {{-- @foreach ($hourlyData as $data) --}}
<tr class="border-b border-gray-100 hover:bg-gray-50">
                                {{-- <td class="py-3 px-4">{{ $data['time'] }}</td>/// --}}
                            </tr>
{{-- @endforeach --}}

                        <tr class="bg-gray-100 font-bold border-t-2 border-gray-300">
                            <td class="py-4 px-4">Total</td>
                            <td></td>
                            <td class="py-4 px-4 text-right">{{ number_format($totalKwh, 2) }}</td>
                            <td class="py-4 px-4 text-right">{{ number_format($totalCost, 0, ',', '.') }}</td>
                        </tr>
                        </tbody>
                    </table> -->
                    </div>
                </div>

            </div>


            <!-- RIGHT SECTION — 7 DAYS -->
            <div class="flex flex-col flex-1 gap-4">

                <!-- Weekly Chart -->
                <div class="bg-white rounded-3xl shadow-lg p-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Grafik Penggunaan 7 Hari Terakhir</h3>
                    <div class="w-full h-[260px]">
                        <canvas id="weekly-chart"></canvas>
                    </div>
                </div>

                <!-- Weekly Table -->
                <div class="bg-white rounded-3xl shadow-lg p-4 flex-1">
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
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="py-3 px-4">{{ $day['date'] }}</td>
                                        <td class="py-3 px-4 text-right font-semibold">
                                            {{ number_format($day['avg_watt'], 2) }}</td>
                                        <td class="py-3 px-4 text-right">{{ number_format($day['kwh'], 2) }}</td>
                                        <td class="py-3 px-4 text-right">{{ number_format($day['cost'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 text-center text-gray-500">
                                            Belum ada data mingguan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- ========================= REALTIME (Volt, Ampere & Watt) ========================= -->
    <script>
        async function fetchRealtimePower() {
            const res = await fetch('/api/realtime');
            const data = await res.json();

            document.querySelector('.total-volt').textContent = Math.round(data?.voltage ?? 0);
            document.querySelector('.total-amp').textContent = data?.amperage ?? 0;
            document.querySelector('.total-power').textContent = Math.round(data?.watt ?? 0);
        }

        setInterval(fetchRealtimePower, 5000);
        fetchRealtimePower();
    </script>


    <!-- ========================= CHART.JS ========================= -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const hourlyLabels = @json($hourlyChartLabels);
        const hourlyData = @json($hourlyChartData);
        const weeklyLabels = @json($weeklyChartLabels);
        const weeklyKwh = @json($weeklyChartKwh);
        const weeklyCost = @json($weeklyChartCost);

        /* ====== 24 HOURS CHART ====== */
        new Chart(document.getElementById('hourly-chart'), {
            type: 'line',
            data: {
                labels: hourlyLabels,
                datasets: [{
                    label: 'Daya (W)',
                    data: hourlyData,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59,130,246,0.15)',
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        /* ====== WEEKLY CHART ====== */
        new Chart(document.getElementById('weekly-chart'), {
            type: 'line',
            data: {
                labels: weeklyLabels,
                datasets: [{
                        label: 'kWh',
                        data: weeklyKwh,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59,130,246,0.15)',
                        yAxisID: 'y',
                        tension: 0.4,
                        borderWidth: 3,
                        fill: true
                    },
                    {
                        label: 'Biaya (Rp)',
                        data: weeklyCost,
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34,197,94,0.15)',
                        yAxisID: 'y1',
                        tension: 0.4,
                        borderWidth: 3,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        position: 'left',
                        title: {
                            display: true,
                            text: 'kWh'
                        }
                    },
                    y1: {
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Biaya'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    },
                }
            }
        });
    </script>

</body>

</html>
