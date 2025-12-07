<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>

    <!-- Exo font -->
    <link href="https://fonts.googleapis.com/css2?family=Exo:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
</head>

<body class="bg-[#E1DFEC] text-gray-900 font-sans">
    <x-header />

    <div class="bg-[#E1DFEC] mx-auto px-2 sm:px-4">
        <x-tab-navigation-home />

        <div class="container mx-auto flex flex-col md:flex-row gap-4 py-2">

            <!-- ========== LEFT COLUMN ========== -->
            <div class="bg-[#d5dbea] shadow-sm rounded-3xl p-4 sm:p-4 flex-1">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Penggunaan energi</h2>
        
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-stretch">
        
                    <!-- TOTAL DAYA + SISA KWH -->
                    <div class="flex flex-col gap-2">
                        <div class="flex flex-row gap-3 justify-between flex-1">
        
                            <!-- TOTAL DAYA -->
                            <div class="flex flex-col flex-1 bg-[#eaeff4] shadow-md rounded-3xl p-4">
                                <div class="relative w-24 h-24 mb-3 flex flex-col items-center justify-center">
                                    <svg class="absolute inset-0 w-full h-full transform -rotate-90"
                                         viewBox="0 0 100 100">
                                        <circle cx="50" cy="50" r="42" stroke="#e5e7eb" stroke-width="8" fill="none"/>
                                        <circle cx="50" cy="50" r="42" stroke="#22c55e" stroke-width="8"
                                                fill="none" stroke-linecap="round"
                                                stroke-dasharray="264" stroke-dashoffset="264"
                                                class="total-power-circle transition-all duration-500 ease-in-out"/>
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-6 w-6 text-black"
                                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                </div>
        
                                <p class="text-3xl font-bold text-gray-900 mt-2 total-power">{{ $realtimeWatt }} W</p>
                                <p class="text-sm text-gray-500 mb-2">dari {{ $maxPower }} W</p>
        
                                <div class="flex justify-center mt-auto">
                                    <a href="{{ route('dashboard.total_daya') }}"
                                       class="w-full bg-[#f5f8f9] shadow-sm rounded-2xl p-3 flex justify-between items-center text-medium font-bold text-gray-800 hover:bg-gray-100">
                                        Total daya
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                                             viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="9" fill="#000000"/>
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  stroke-width="1.5"
                                                  stroke="#FFFFFF" d="M10 7.5l5 4.5-5 4.5"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
        
                            @if($billingType === 'prabayar' && $remainingKwh !== null)
                            <!-- SISA KWH (Only for Prabayar) -->
                            <div class="flex flex-col flex-1 bg-[#eaeff4] shadow-md rounded-3xl p-4">
                                <div class="relative w-24 h-24 mb-3 flex flex-col items-center justify-center">
                                    <svg class="absolute inset-0 w-full h-full transform -rotate-90"
                                         viewBox="0 0 100 100">
                                        <circle cx="50" cy="50" r="42" stroke="#e5e7eb" stroke-width="8" fill="none"/>
                                        <circle cx="50" cy="50" r="42" stroke="#22c55e" stroke-width="8"
                                                fill="none" stroke-linecap="round"
                                                stroke-dasharray="264" stroke-dashoffset="264"
                                                class="remaining-kwh-circle transition-all duration-500 ease-in-out"/>
                                    </svg>
                                    <img src="{{ asset('images/icon_kWh.png') }}" class="w-6 h-6 sm:w-8 sm:h-8">
                                </div>
        
                                <p class="text-3xl font-bold text-gray-900 mt-2 remaining-kwh">
                                    {{ number_format($remainingKwh, 2) }}
                                </p>
                                <p class="text-sm text-gray-500 mb-2">kWh</p>
        
                                <div class="flex justify-center mt-auto">
                                    <a href="{{ route('dashboard.sisa_kwh') }}"
                                       class="w-full bg-[#f5f8f9] shadow-sm rounded-2xl p-3 flex justify-between items-center text-medium font-bold text-gray-800 hover:bg-gray-100">
                                        Sisa kWh
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                                             viewBox="0 0 24 24">
                                            <circle cx="12" cy="12" r="9" fill="#000000"/>
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  stroke-width="1.5"
                                                  stroke="#FFFFFF" d="M10 7.5l5 4.5-5 4.5"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            @endif
        
                        </div>
                    </div>
        
                    <!-- SENSOR CARDS -->
                    <div class="flex flex-col gap-3">
        
                        <div class="bg-white rounded-3xl shadow-md p-4 flex flex-col justify-between flex-1">
                            <h2 class="font-semibold text-gray-500">Terakhir pengisian daya</h2>
                            <div class="flex justify-between items-center mt-auto">
                                <p class="text-xl font-semibold">{{ $lastCharge }}</p>
                                <img src="{{ asset('images/icon_kWh.png') }}" class="w-10 h-10">
                            </div>
                        </div>
        
                        <div class="bg-white rounded-3xl shadow-md p-4 flex flex-col">
                            <h2 class="font-semibold text-gray-500">Rata-rata penggunaan daya</h2>
                            <div class="flex justify-between items-center mt-auto">
                                <p class="text-xl font-semibold">{{ round($avgWatt) }} W/jam</p>
                                <img src="{{ asset('images/icon_kWh.png') }}" class="w-10 h-10">
                            </div>
                        </div>
        
                        <div class="bg-white rounded-3xl shadow-md p-4 flex flex-col">
                            <h2 class="font-semibold text-gray-500">Rata-rata penggunaan energi</h2>
                            <div class="flex justify-between items-center mt-auto">
                                <p class="text-xl font-semibold">Rp{{ number_format($avgCost, 0, ',', '.') }}/jam</p>
                                <img src="{{ asset('images/icon_kWh.png') }}" class="w-10 h-10">
                            </div>
                        </div>
        
                    </div>
                </div>
                <!-- SEPARATE 24 Hour Chart Card INSIDE LEFT COLUMN -->
                <div class="bg-[#eaeff4] rounded-3xl shadow-lg p-4 flex flex-col mt-4">
                    <div class="flex flex-row items-center justify-between w-full mb-2">
                        <h3 class="text-lg font-bold text-gray-900">Grafik Penggunaan Daya (24 Jam)</h3>
                        <span class="text-gray-500 text-sm ml-3">Terakhir update: {{ $lastCharge ?? '-' }}</span>
                    </div>
                    <div class="w-full h-[250px] mb-4">
                        <canvas class="bg-[#f5f8f9] rounded-2xl" id="hourly-chart-home"></canvas>
                    </div>
                    <div class="w-full flex justify-end">
                        <a href="{{ route('dashboard.total_daya') }}"
                           class="bg-[#f5f8f9] shadow-sm rounded-2xl p-3 flex justify-between items-center text-medium font-bold text-gray-800 hover:bg-gray-100">
                            Lihat Detail
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 ml-1" fill="none"
                                 viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="9" fill="#000000"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="1.5"
                                      stroke="#FFFFFF" d="M10 7.5l5 4.5-5 4.5"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <!-- END SEPARATE 24 Hour Chart Card INSIDE LEFT COLUMN -->
            </div> <!-- END LEFT COLUMN -->

            <!-- ========== RIGHT COLUMN ========== -->
            <div class="bg-[#d5dbea] shadow-sm rounded-3xl p-4 flex-1">
                <h1 class="text-lg font-bold mb-4">Pengguna</h1>
                <!-- Customer Info -->
                <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                        <div>
                            <p class="text-xs text-gray-500 mb-1">ID Pelanggan</p>
                            <p class="text-xl font-bold">{{ $pelangganId }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Nama Pelanggan</p>
                            <p class="text-xl font-bold">{{ Auth::user()->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Jenis Pelanggan</p>
                            <p class="text-xl font-bold">
                                {{ $maxPower }} VA • {{ ucfirst($billingType) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Isi Terakhir</p>
                            <p class="text-xl font-bold">{{ $lastCharge }}</p>
                        </div>

                    </div>
                </div>
                <!-- BOTTOM — Weekly Chart (Home) -->
                <div class="bg-white rounded-3xl shadow-lg p-4 mt-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Grafik Penggunaan 7 Hari Terakhir</h3>
                    <div class="w-full h-[260px]">
                        <canvas id="weekly-chart-home"></canvas>
                    </div>
                </div>
                <!-- END BOTTOM -->
            </div>
            <!-- END RIGHT COLUMN -->
        </div> <!-- END flex-row -->

    </div>
    <!-- END bg-[#E1DFEC] mx-auto px-2 sm:px-4 -->

    <x-bottom-navigation />

</body>

<script>
    async function fetchRealtimePower() {
        const res = await fetch('/api/realtime');
        const data = await res.json();

        const watt = Math.round(data?.watt || 0);
        document.querySelector('.total-power').textContent = `${watt} W`;

        // 🔥 Perubahan penting: dari 1300 → variabel dinamis
        const maxPower = {{ $maxPower }};
        const percent = Math.min((watt / maxPower) * 100, 100);

        const circle = document.querySelector('.total-power-circle');
        circle.style.strokeDashoffset = 264 - (264 * percent / 100);

        if (watt >= maxPower * 0.9) circle.style.stroke = '#ef4444';
        else if (watt >= maxPower * 0.7) circle.style.stroke = '#facc15';
        else circle.style.stroke = '#22c55e';
    }

    setInterval(fetchRealtimePower, 5000);
    fetchRealtimePower();
</script>

<script>
    @if($billingType === 'prabayar' && $remainingKwh !== null)
    function updateRemainingKwhCircle() {
        const remainingKwh = {{ $remainingKwh }};
        const circle = document.querySelector('.remaining-kwh-circle');
        
        if (!circle) return;
        
        let percent, color;
        
        if (remainingKwh > 20) {
            // kWh > 20: full hijau
            percent = 100;
            color = '#22c55e'; // green
        } else if (remainingKwh >= 10) {
            // 10 <= kWh <= 20: full kuning
            percent = 100;
            color = '#facc15'; // yellow
        } else {
            // kWh < 10: merah dengan persentase berdasarkan sisa kWh
            // Persentase = (kWh / 10) * 100
            percent = (remainingKwh / 10) * 100;
            color = '#ef4444'; // red
        }
        
        // Update circle
        circle.style.strokeDashoffset = 264 - (264 * percent / 100);
        circle.style.stroke = color;
    }
    
    // Initialize on page load
    updateRemainingKwhCircle();
    
    // Update periodically (every 5 seconds)
    setInterval(updateRemainingKwhCircle, 5000);
    @endif
</script>

<script>
    const hourlyLabelsHome = @json($hourlyChartLabels ?? []);
    const hourlyDataHome = @json($hourlyChartData ?? []);
    const weeklyLabelsHome = @json($weeklyChartLabels ?? []);
    const weeklyKwhHome = @json($weeklyChartKwh ?? []);
    const weeklyCostHome = @json($weeklyChartCost ?? []);

    // Hourly chart for home
    if (document.getElementById('hourly-chart-home')) {
        new Chart(document.getElementById('hourly-chart-home'), {
            type: 'line',
            data: {
                labels: hourlyLabelsHome,
                datasets: [{
                    label: 'Daya (W)',
                    data: hourlyDataHome,
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
    }

    // Weekly chart for home
    if (document.getElementById('weekly-chart-home')) {
        new Chart(document.getElementById('weekly-chart-home'), {
            type: 'line',
            data: {
                labels: weeklyLabelsHome,
                datasets: [
                    {
                        label: 'kWh',
                        data: weeklyKwhHome,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59,130,246,0.15)',
                        yAxisID: 'y',
                        tension: 0.4,
                        borderWidth: 3,
                        fill: true
                    },
                    {
                        label: 'Biaya (Rp)',
                        data: weeklyCostHome,
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
                    }
                }
            }
        });
    }
</script>

</html>
