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
            databaseURL: ENV('FIREBASE_DATABASE_URL'),
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

<body class="bg-[#E1DFEC] text-gray-900 font-sans">
    <x-header />
    <x-tab-navigation-home />
    <div class="bg-[#E1DFEC] mx-auto px-2 sm:px-4">
        <div class="container mx-auto flex flex-col md:flex-row gap-4 py-2">
            <!-- Left Column -->
            <div class="bg-[#d5dbea] shadow-sm rounded-3xl p-4 sm:p-4 flex-1">
                <h2 class="text-lg font-semibold text-gray-800 mb-2">Penggunaan energi</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-stretch">
                    <!-- Card penggunaan energi -->
                    <div class="flex flex-col gap-2">
                        <div class="flex flex-row gap-3 justify-between flex-1">
                            <!-- Total daya -->
                            <div class="flex flex-col flex-1 bg-[#eaeff4] shadow-md rounded-3xl p-4">
                                <div class="relative w-24 h-24 mb-3 flex flex-col items-center justify-center">
                                    <svg class="absolute inset-0 w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                                        <circle cx="50" cy="50" r="42" stroke="#e5e7eb" stroke-width="8" fill="none" />
                                        <circle cx="50" cy="50" r="42" stroke="#22c55e" stroke-width="8" fill="none" stroke-linecap="round" stroke-dasharray="264" stroke-dashoffset="264" class="total-power-circle transition-all duration-500 ease-in-out" />
                                    </svg>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </div>
                                </div>
                                <p class="text-3xl font-bold text-gray-900 mt-2 total-power">NULL</p>
                                <p class="text-sm text-gray-500 mb-2">dari 1300 W</p>
                                <div class="flex justify-center mt-auto">
                                    <button class="w-full bg-[#f5f8f9] shadow-sm rounded-2xl p-3 flex justify-between items-center text-medium font-bold text-gray-800">
                                        Total daya
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-6 h-6">
                                            <circle cx="12" cy="12" r="9" fill="#000000" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" stroke="#FFFFFF" d="M10 7.5l5 4.5-5 4.5" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Sisa kWh -->
                            <div class="flex flex-col flex-1 bg-[#eaeff4] shadow-md rounded-3xl p-4">
                                <div class="relative w-24 h-24 mb-3 flex flex-col items-center justify-center">
                                    <svg class="absolute inset-0 w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                                        <circle cx="50" cy="50" r="42" stroke="#e5e7eb" stroke-width="8" fill="none" />
                                        <circle cx="50" cy="50" r="42" stroke="#22c55e" stroke-width="8" fill="none" stroke-linecap="round" stroke-dasharray="264" stroke-dashoffset="264" class="remaining-kwh-circle transition-all duration-500 ease-in-out" />
                                    </svg>
                                    <img src="{{ asset('images/icon_kWh.png') }}" alt="icon meter" class="w-6 h-6 sm:w-8 sm:h-8">
                                </div>
                                <p class="text-3xl font-bold text-gray-900 mt-2 remaining-kwh">16.82</p>
                                <p class="text-sm text-gray-500 mb-2">kWh</p>
                                <div class="flex justify-center mt-auto">
                                    <button class="w-full bg-[#f5f8f9] shadow-sm rounded-2xl p-3 flex justify-between items-center text-medium font-bold text-gray-800">
                                        Sisa kWh
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-6 h-6">
                                            <circle cx="12" cy="12" r="9" fill="#000000" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" stroke="#FFFFFF" d="M10 7.5l5 4.5-5 4.5" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Bacaan Sensor -->
                    <div class="flex flex-col gap-3">
                        <!-- Sensor 1 -->
                        <div class="bg-white rounded-3xl shadow-md p-4 flex flex-col justify-between flex-1">
                            <h2 class="font-semibold text-gray-500">Terakhir pengisian daya</h2>
                            <div class="flex justify-between items-center mt-auto">
                                <p class="text-xl font-semibold text-gray-800 mt-1 last-charge-date">22/12/2022</p>
                                <img src="{{ asset('images/icon_kWh.png') }}" alt="icon meter" class="w-10 h-10">
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl shadow-md p-4 flex flex-col justify-between flex-1">
                            <h2 class="font-semibold text-gray-500">Rata-rata penggunaan daya</h2>
                            <div class="flex justify-between items-center mt-auto">
                                <p class="text-xl font-semibold last-charge-date"><span>122</span> W/jam</p>
                                <img src="{{ asset('images/icon_kWh.png') }}" alt="icon meter" class="w-10 h-10">
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl shadow-md p-4 flex flex-col justify-between flex-1">
                            <h2 class="font-semibold text-gray-500">Rata-rata penggunaan energi</h2>
                            <div class="flex justify-between items-center mt-auto">
                                <p class="text-xl font-semibold last-charge-date"><span>30</span> Wh/jam</p>
                                <img src="{{ asset('images/icon_kWh.png') }}" alt="icon meter" class="w-10 h-10">
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="bg-[#d5dbea] shadow-sm rounded-3xl p-4 sm:p-4 flex-1">
                <div class="bg-[#d5dce8]">
                    <!-- Header -->
                    <h1 class="text-xl font-bold text-gray-900 mb-6">Pengguna</h1>

                    <!-- Alert Banner with Payment Button -->
                    <div class="bg-[#e8ed3d] rounded-3xl p-4 mb-4 flex justify-between items-center shadow-md">
                        <p class="text-lg text-gray-900">
                            Sisa kWh <span class="font-bold">16.82</span>, lakukan pembayaran listrik.
                        </p>
                        <button class="bg-white hover:bg-gray-50 transition-colors rounded-2xl px-6 py-3 flex items-center gap-3 font-bold text-gray-900 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            Pembayaran
                        </button>
                    </div>

                    <!-- Customer Info Card -->
                    <div class="bg-white rounded-3xl shadow-md p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- ID Pelanggan -->
                            <div>
                                <p class="text-sm text-gray-500 mb-2">ID Pelanggan</p>
                                <p class="text-2xl font-bold text-gray-900">123876544</p>
                            </div>

                            <!-- Nama Pelanggan -->
                            <div>
                                <p class="text-sm text-gray-500 mb-2">Nama pelanggan</p>
                                <p class="text-2xl font-bold text-gray-900">John Doe</p>
                            </div>

                            <!-- Jenis Pelanggan -->
                            <div>
                                <p class="text-sm text-gray-500 mb-2">Jenis Pelanggan</p>
                                <p class="text-2xl font-bold text-gray-900">1300 VA - Prabayar</p>
                            </div>

                            <!-- Isi Terakhir -->
                            <div>
                                <p class="text-sm text-gray-500 mb-2">Isi Terakhir</p>
                                <p class="text-2xl font-bold text-gray-900">22 Januari 2025</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Right Column -->

    </div>


    <!-- Mobile Bottom Navigation -->
    <x-bottom-navigation />
</body>

</html>