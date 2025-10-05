<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <!-- Exo font from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Exo:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Firebase SDK -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-app.js";
        import { getDatabase, ref, get } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-database.js";

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

                    // Update total power (wattage)
                    const totalPowerElement = document.querySelector('.total-power');
                    if (totalPowerElement && data.wattage !== undefined) {
                        totalPowerElement.textContent = data.wattage.toFixed(2);
                        // Update progress bar (assuming max 1300 W)
                        const progress = (data.wattage / 1300) * 377;
                        const offset = 377 - progress;
                        document.querySelector('.total-power-circle').setAttribute('stroke-dashoffset', offset);
                    }

                    // Note: remainingKwh, lastChargeDate, averageUsage not in provided data
                    // Add them to your Firebase data or adjust accordingly
                    console.log("Data fetched:", data);
                } else {
                    console.log("No data available");
                }
            } catch (error) {
                console.error("Error fetching data: ", error);
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

<body class="bg-[#E1DFEC] text-gray-900 font-sans">
    <x-header />
    <main class="container mx-auto mt-4 px-2 sm:px-4">
        <div class="flex flex-col md:flex-row gap-4 justify-center items-stretch">
            <!-- Left Column -->
            <div class="bg-gray-100 rounded-xl p-4 sm:p-6 shadow-lg flex-1">
                <h2 class="text-lg font-semibold mb-4 text-gray-800">Penggunaan energi</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Sisa kWh -->
                    <div class="bg-white rounded-xl shadow p-4 flex flex-col">
                        <div class="relative w-24 h-24 sm:w-32 sm:h-32 flex items-center justify-center">
                            <svg class="absolute inset-0 w-full h-full transform -rotate-90">
                                <circle cx="50%" cy="50%" r="45%" stroke="#e5e7eb" stroke-width="10" fill="none" />
                                <circle cx="50%" cy="50%" r="45%" stroke="#facc15" stroke-width="10"
                                    fill="none" stroke-linecap="round" stroke-dasharray="377" stroke-dashoffset="93" class="remaining-kwh-circle" />
                            </svg>
                            <div class="flex flex-col items-center">
                                <img src="{{ asset('images/icon_kWh.png') }}" alt="icon meter" class="w-8 h-8 sm:w-10 sm:h-10">
                                <p class="text-xl font-bold text-gray-800 remaining-kwh">16.82</p>
                                <p class="text-sm text-gray-500">kWh</p>
                            </div>
                        </div>
                        <p class="text-gray-700 font-medium mt-2">Sisa kWh</p>
                        <a href="#" class="text-xs text-gray-400 underline">Riwayat</a>
                    </div>
                    <!-- Total daya -->
                    <div class="bg-white rounded-xl shadow p-4 flex flex-col">
                        <div class="relative w-24 h-24 sm:w-32 sm:h-32 flex items-center justify-center">
                            <svg class="absolute inset-0 w-full h-full transform -rotate-90">
                                <circle cx="50%" cy="50%" r="45%" stroke="#e5e7eb" stroke-width="10" fill="none" />
                                <circle cx="50%" cy="50%" r="45%" stroke="#22c55e" stroke-width="10"
                                    fill="none" stroke-linecap="round" stroke-dasharray="377" stroke-dashoffset="333" class="total-power-circle" />
                            </svg>
                            <div class="flex flex-col items-center">
                                <img src="{{ asset('images/icon_watt.png') }}" alt="icon meter" class="w-6 h-6 sm:w-10 sm:h-10">
                                <p class="text-xl font-bold text-gray-800 total-power">97.92</p>
                                <p class="text-sm text-gray-500">/<span class="user_maxDaya"> 1300</span>W</p>
                            </div>
                        </div>
                        <p class="text-gray-700 font-medium mt-2">Total daya</p>
                        <a href="#" class="text-xs text-gray-400 underline">Detail</a>
                    </div>
                </div>
            </div>
            <!-- Right Column -->
            <div class="bg-gray-100 rounded-xl p-4 sm:p-6 shadow-lg flex-1 flex flex-col gap-4">
                <h2 class="text-lg font-semibold text-gray-800">Penggunaan energi</h2>
                <div class="bg-white rounded-2xl shadow p-4 sm:p-5 w-full flex justify-between items-center">
                    <div>
                    <p class="text-sm font-semibold text-gray-500">Terakhir pengisian daya</p>
                    <p class="text-xl text-gray-800 mt-1 last-charge-date">22/12/2022</p>
                    </div>
                    <img src="{{ asset('images/icon_kWh.png') }}" alt="icon meter" class="w-6 h-6 sm:w-10 sm:h-10">
                </div>
                <div class="bg-white rounded-2xl shadow p-4 sm:p-5 w-full flex justify-between items-center">
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Rata-rata penggunaan kWh</p>
                        <p class="text-xl text-gray-800 mt-1 average-usage">30 Wh/jam</p>
                    </div>
                    <img src="{{ asset('images/icon_watt.png') }}" alt="icon listrik" class="w-6 h-6 sm:w-10 sm:h-10">
                </div>
            </div>
        </div>
    </main>
    <!-- Mobile Bottom Navigation -->
    <x-bottom-navigation />
</body>

</html>