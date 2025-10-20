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

<body class="bg-[#000000] text-gray-900 font-sans">

    <x-header />
    <div class="bg-[#E1DFEC] mx-auto px-2 sm:px-4">
    <x-tab-navigation-home />
    <div class="container mx-auto rounded-3xl my-2">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Detail listrik pelanggan</h2>
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-2">
            <div class="bg-white rounded-2xl p-4 shadow-sm">
                <p class="text-sm text-gray-600 mb-1">Sisa kWh</p>
                <p class="text-2xl font-bold text-gray-900">9.82 <span class="text-base font-normal">kWh</span></p>
            </div>
            <div class="bg-white rounded-2xl p-4 shadow-sm">
                <p class="text-sm text-gray-600 mb-1">Isi Terakhir</p>
                <p class="text-xl font-bold text-gray-900">22 Januari 2025</p>
            </div>
            <div class="bg-white rounded-2xl p-4 shadow-sm">
                <p class="text-sm text-gray-600 mb-1">Ampere (A)</p>
                <p class="text-2xl font-bold text-gray-900"><span>3.22</span> A</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-4">
                <p class="text-xs text-gray-600 mb-1">Rata-rata kWh/hari</p>
                <p class="text-2xl font-bold text-gray-900"><span>14.14</span> kWh</p>
            </div>
            
        </div>
    </div>
        <div class="container mx-auto flex flex-col md:flex-row gap-4 py-2">
    
            <!-- Left: Data Harian (24 Jam) -->
            <div class="flex-1">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Data Harian Penggunaan Listrik</h2>
    
                <!-- Line Chart Harian -->
                <div class="bg-white rounded-3xl shadow-lg p-6 mb-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Grafik Penggunaan Listrik (24 Jam)</h3>
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
                                    <th class="text-right py-3 px-4 font-bold text-gray-700">Sisa kWh</th>
                                    <th class="text-right py-3 px-4 font-bold text-gray-700">Penggunaan kWh</th>
                                    <th class="text-right py-3 px-4 font-bold text-gray-700">Biaya (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hourlyKwh as $data)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                    <td class="py-3 px-4 text-gray-900">{{ $data['time'] }}</td>
                                    <td class="py-3 px-4 text-right text-gray-900 font-semibold">{{ $data['remaining_kwh'] }}</td>
                                    <td class="py-3 px-4 text-right text-gray-900">{{ number_format($data['kwh'], 2) }}</td>
                                    <td class="py-3 px-4 text-right text-gray-900">{{ number_format($data['cost'], 0, ',', '.') }}</td>
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

        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Data dari Laravel Controller
        const hourlyChartLabels = @json($hourlyChartLabels);
        const hourlyChartData = @json($hourlyChartData);
    
        // Chart 24 Jam
        const hourlyCtx = document.getElementById('hourly-chart').getContext('2d');
        new Chart(hourlyCtx, {
            type: 'line',
            data: {
                labels: hourlyChartLabels,
                datasets: [{
                    label: 'kWh',
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
                            text: 'Sisa kWh'
                        },
                        ticks: {
                            callback: function(value) {
                                return value;
                            }
                        }
                    }
                }
            }
        });
    </script>

</body>
</html>