<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total daya</title>
    <!-- Exo font from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Exo:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Firebase SDK -->
    <script type="module">
        import {
            initializeApp
        } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-app.js";
        import {
            getDatabase,
            ref,
            get
        } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-database.js";

        // Firebase configuration - REPLACE WITH YOUR ACTUAL CONFIG
        const firebaseConfig = {
            apiKey: "your-api-key",
            authDomain: "your-project.firebaseapp.com",
            projectId: "powergo-bismillah",
            databaseURL: "https://powergo-bismillah-default-rtdb.firebaseio.com/",
            storageBucket: "your-project.appspot.com",
            messagingSenderId: "123456789",
            appId: "your-app-id"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const db = getDatabase(app);

        // Function to fetch and update data
        async function updateDashboardData() {
            try {
                const dbRef = ref(db, 'sensor/-OakoQevOeQxnT0_7ydo');
                const snapshot = await get(dbRef);

                if (snapshot.exists()) {
                    const data = snapshot.val();
                    const wattage = parseFloat(data.wattage) || 0;
                    const maxWatt = 1300;
                    const dashArray = 295;

                    // Loop semua elemen dengan class total-power
                    document.querySelectorAll('.total-power').forEach(el => {
                        el.textContent = wattage.toFixed(2);
                    });

                    // Loop semua lingkaran
                    document.querySelectorAll('.total-power-circle').forEach(circle => {
                        // Hitung progress (0 = kosong, full circle = 1300W)
                        let offset = dashArray - (wattage / maxWatt) * dashArray;
                        if (offset < 0) offset = 0;
                        if (offset > dashArray) offset = dashArray;
                        circle.setAttribute('stroke-dashoffset', offset);

                        // Warna otomatis
                        if (wattage <= 900) {
                            circle.setAttribute('stroke', '#22c55e'); // hijau
                        } else if (wattage <= 1200) {
                            circle.setAttribute('stroke', '#facc15'); // kuning
                        } else {
                            circle.setAttribute('stroke', '#ef4444'); // merah
                        }
                    });

                    console.log("✅ Data fetched:", data);
                } else {
                    console.log("⚠️ No data available");
                }
            } catch (error) {
                console.error("❌ Error fetching data: ", error);
            }
        }


        // Call the function when DOM is loaded
        document.addEventListener('DOMContentLoaded', updateDashboardData);
    </script>
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

<!-- <body class="bg-[#E1DFEC] text-gray-900 font-sans"> -->

    <x-header />
    <div class="bg-[#E1DFEC] mx-auto px-2 sm:px-4">
    <x-tab-navigation-home />
        <div class="container mx-auto flex flex-col md:flex-row gap-4 py-2">
    
            <!-- Left: Data Harian (24 Jam) -->
            <div class="flex-1">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Data Harian Penggunaan Daya</h2>
    
                <!-- Line Chart Harian -->
                <div class="bg-white rounded-3xl shadow-lg p-6 mb-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Grafik Penggunaan Daya (24 Jam)</h3>
                    <div class="relative w-full h-[300px]">
                        <canvas id="hourly-chart"></canvas>
                    </div>
                </div>
    
                <!-- Tabel Harian -->
                <div class="bg-white rounded-3xl shadow-lg p-6">
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
                                @foreach($hourlyData as $data)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                    <td class="py-3 px-4 text-gray-900">{{ $data->time }}</td>
                                    <td class="py-3 px-4 text-right text-gray-900 font-semibold">{{ $data->watt }}</td>
                                    <td class="py-3 px-4 text-right text-gray-900">{{ number_format($data->kwh, 2) }}</td>
                                    <td class="py-3 px-4 text-right text-gray-900">{{ number_format($data->cost, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                                <tr class="bg-gray-100 font-bold border-t-2 border-gray-300">
                                    <td class="py-4 px-4 text-gray-900">Total</td>
                                    <td class="py-4 px-4 text-right text-gray-900">-</td>
                                    <td class="py-4 px-4 text-right text-gray-900">{{ number_format($totalKwh, 2) }}</td>
                                    <td class="py-4 px-4 text-right text-gray-900">{{ number_format($totalCost, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    
            <!-- Right: Data 7 Hari Terakhir -->
            <div class="flex-1">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Data 7 Hari Terakhir</h2>
    
                <!-- Summary Cards -->
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="bg-white rounded-2xl shadow-md p-4">
                        <p class="text-xs text-gray-600 mb-1">Total kWh</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($weeklyTotalKwh, 2) }}</p>
                        <p class="text-xs text-gray-500 mt-1">7 hari</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-md p-4">
                        <p class="text-xs text-gray-600 mb-1">Rata-rata kWh/hari</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($weeklyAvgKwh, 2) }}</p>
                        <p class="text-xs text-gray-500 mt-1">per hari</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-md p-4">
                        <p class="text-xs text-gray-600 mb-1">Total Biaya</p>
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($weeklyTotalCost, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500 mt-1">7 hari</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-md p-4">
                        <p class="text-xs text-gray-600 mb-1">Rata-rata Daya</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($weeklyAvgWatt, 0) }} W</p>
                        <p class="text-xs text-gray-500 mt-1">per hari</p>
                    </div>
                </div>
    
                <!-- Line Chart 7 Hari -->
                <div class="bg-white rounded-3xl shadow-lg p-6 mb-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Grafik Penggunaan 7 Hari Terakhir</h3>
                    <div class="relative w-full h-[350px]">
                        <canvas id="weekly-chart"></canvas>
                    </div>
                </div>
    
                <!-- Tabel 7 Hari -->
                <div class="bg-white rounded-3xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Detail Penggunaan Per Hari</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-gray-200">
                                    <th class="text-left py-3 px-4 font-bold text-gray-700">Tanggal</th>
                                    <th class="text-right py-3 px-4 font-bold text-gray-700">Rata-rata Daya (W)</th>
                                    <th class="text-right py-3 px-4 font-bold text-gray-700">Total kWh</th>
                                    <th class="text-right py-3 px-4 font-bold text-gray-700">Biaya (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($weeklyData as $data)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                    <td class="py-3 px-4 text-gray-900">{{ $data->date }}</td>
                                    <td class="py-3 px-4 text-right text-gray-900 font-semibold">{{ number_format($data->avg_watt, 0) }}</td>
                                    <td class="py-3 px-4 text-right text-gray-900 font-semibold">{{ number_format($data->kwh, 2) }}</td>
                                    <td class="py-3 px-4 text-right text-gray-900">{{ number_format($data->cost, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                                <tr class="bg-gray-100 font-bold border-t-2 border-gray-300">
                                    <td class="py-4 px-4 text-gray-900">Total</td>
                                    <td class="py-4 px-4 text-right text-gray-900">{{ number_format($weeklyAvgWatt, 0) }} (avg)</td>
                                    <td class="py-4 px-4 text-right text-gray-900">{{ number_format($weeklyTotalKwh, 2) }}</td>
                                    <td class="py-4 px-4 text-right text-gray-900">{{ number_format($weeklyTotalCost, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
    
            </div>
        </div>
    </div>
    
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

</html>