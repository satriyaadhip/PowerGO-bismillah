<!DOCTYPE html><html lang="en"><head>
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
    <div class="bg-[#E1DFEC] mx-auto px-2 sm:px-4">  <x-tab-navigation-home />  
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
                                <svg class="absolute inset-0 w-full h-full transform -rotate-90"  
                                    viewBox="0 0 100 100">  
                                    <circle cx="50" cy="50" r="42" stroke="#e5e7eb" stroke-width="8"  
                                        fill="none" />  
                                    <circle cx="50" cy="50" r="42" stroke="#22c55e" stroke-width="8"  
                                        fill="none" stroke-linecap="round" stroke-dasharray="264"  
                                        stroke-dashoffset="264"  
                                        class="total-power-circle transition-all duration-500 ease-in-out" />  
                                </svg>  
                                <div class="absolute inset-0 flex items-center justify-center">  
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black"  
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">  
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"  
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />  
                                    </svg>  
                                </div>  
                            </div>  
                            <p class="text-3xl font-bold text-gray-900 mt-2 total-power">NULL</p>  
                            <p class="text-sm text-gray-500 mb-2">dari 1300 W</p>  
                            <div class="flex justify-center mt-auto">  
                                <a href="{{ route('dashboard.total_daya') }}"  
                                    class="w-full bg-[#f5f8f9] shadow-sm rounded-2xl p-3 flex justify-between items-center text-medium font-bold text-gray-800 transition-colors hover:bg-gray-100">  
                                    Total daya  
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"  
                                        class="w-6 h-6">  
                                        <circle cx="12" cy="12" r="9" fill="#000000" />  
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"  
                                            stroke="#FFFFFF" d="M10 7.5l5 4.5-5 4.5" />  
                                    </svg>  
                                </a>  
                            </div>  
                        </div>  

                        <!-- Sisa kWh -->  
                        <div class="flex flex-col flex-1 bg-[#eaeff4] shadow-md rounded-3xl p-4">  
                            <div class="relative w-24 h-24 mb-3 flex flex-col items-center justify-center">  
                                <svg class="absolute inset-0 w-full h-full transform -rotate-90"  
                                    viewBox="0 0 100 100">  
                                    <circle cx="50" cy="50" r="42" stroke="#e5e7eb" stroke-width="8"  
                                        fill="none" />  
                                    <circle cx="50" cy="50" r="42" stroke="#22c55e" stroke-width="8"  
                                        fill="none" stroke-linecap="round" stroke-dasharray="264"  
                                        stroke-dashoffset="264"  
                                        class="remaining-kwh-circle transition-all duration-500 ease-in-out" />  
                                </svg>  
                                <img src="{{ asset('images/icon_kWh.png') }}" alt="icon meter"  
                                    class="w-6 h-6 sm:w-8 sm:h-8">  
                            </div>  
                            <p class="text-3xl font-bold text-gray-900 mt-2 remaining-kwh">16.82</p>  
                            <p class="text-sm text-gray-500 mb-2">kWh</p>  
                            <div class="flex justify-center mt-auto">  
                                <a href="{{ route('dashboard.sisa_kwh') }}"  
                                    class="w-full bg-[#f5f8f9] shadow-sm rounded-2xl p-3 flex justify-between items-center text-medium font-bold text-gray-800 transition-colors hover:bg-gray-100">  
                                    Sisa kWh  
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"  
                                        class="w-6 h-6">  
                                        <circle cx="12" cy="12" r="9" fill="#000000" />  
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"  
                                            stroke="#FFFFFF" d="M10 7.5l5 4.5-5 4.5" />  
                                    </svg>  
                                </a>  
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
                            <p class="text-xl font-semibold"><span>{{ round($avgWatt ?? 0) }}</span> W/jam</p>
                            <img src="{{ asset('images/icon_kWh.png') }}" alt="icon meter" class="w-10 h-10">  
                        </div>  
                    </div>  

                    <div class="bg-white rounded-3xl shadow-md p-4 flex flex-col justify-between flex-1">  
                        <h2 class="font-semibold text-gray-500">Rata-rata penggunaan energi</h2>  
                        <div class="flex justify-between items-center mt-auto">  
                            <p class="text-xl font-semibold"><span>{{ round($avgWh ?? 0) }}</span> Wh/jam</p>
                            <img src="{{ asset('images/icon_kWh.png') }}" alt="icon meter" class="w-10 h-10">  
                        </div>  
                    </div>  

                </div>  
            </div>  
        </div> 


            <div class="bg-[#d5dbea] shadow-sm rounded-3xl p-4 sm:p-4 flex-1">
                <div class="bg-transparent">
                  <!-- Header -->
                  <h1 class="text-2xl font-bold text-gray-900 mb-4">Pengguna</h1>
              
                  <!-- Redesigned Alert Banner -->
                  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-yellow-300/80 rounded-2xl p-4 mb-6 shadow-md border border-yellow-400/30">
                    <div>
                      <p class="text-base font-medium text-gray-900">
                        Sisa kWh <span class="font-semibold">16.82</span>
                      </p>
                      <p class="text-sm text-gray-600 mt-1">Saldo menipis — lakukan pengisian agar layanan tetap aktif.</p>
                    </div>
                    <div class="flex items-center gap-3">
                      <a href="{{ route('dashboard.sisa_kwh') }}" class="px-4 py-2 rounded-lg bg-white hover:bg-gray-50 text-sm font-semibold shadow-sm border border-gray-100">
                        Lihat Sisa kWh
                      </a>
                      <button class="flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-green-500 to-teal-400 text-white font-semibold shadow hover:from-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Pembayaran
                      </button>
                    </div>
                  </div>
              
                  <!-- Customer Info Card -->
                  <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                      <!-- ID Pelanggan -->
                      <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">ID Pelanggan</p>
                        <p class="text-xl font-bold text-gray-900">123876544</p>
                      </div>
              
                      <!-- Nama Pelanggan -->
                      <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Nama Pelanggan</p>
                        <p class="text-xl font-bold text-gray-900">John Doe</p>
                      </div>
              
                      <!-- Jenis Pelanggan -->
                      <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Jenis Pelanggan</p>
                        <p class="text-xl font-bold text-gray-900">1300 VA • Prabayar</p>
                      </div>
              
                      <!-- Isi Terakhir -->
                      <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Isi Terakhir</p>
                        <p class="text-xl font-bold text-gray-900">22 Januari 2025</p>
                      </div>
                    </div>
              
                    <!-- Optional details row -->
                    <div class="mt-6 border-t pt-4 flex flex-col sm:flex-row gap-4 sm:items-center sm:justify-between">
                      <div class="flex items-center gap-3">
                        <img src="{{ asset('images/icon_kWh.png') }}" alt="icon" class="w-10 h-10">
                        <div>
                          <p class="text-sm text-gray-500">Rata-rata pemakaian</p>
                          <p class="text-lg font-semibold text-gray-900">122 W/jam</p>
                        </div>
                      </div>
                      <div class="flex items-center gap-6">
                        <div>
                          <p class="text-xs text-gray-500">Estimasi biaya hari ini</p>
                          <p class="font-semibold text-gray-900">Rp 34.800</p>
                        </div>
                        <a href="{{ route('dashboard.total_daya') }}" class="text-sm text-teal-600 font-semibold hover:underline">Lihat detail</a>
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
<script>
    async function fetchRealtimePower() {
        const res = await fetch('/api/realtime');
        const data = await res.json();

        const watt = Math.round(data?.watt || 0);
        document.querySelector('.total-power').textContent = `${watt} W`;

        // Hitung progress
        const maxPower = 1300;
        const percentage = Math.min((watt / maxPower) * 100, 100);
        const circle = document.querySelector('.total-power-circle');

        // Ubah panjang stroke
        circle.style.strokeDashoffset = 264 - (264 * percentage) / 100;

        // Ubah warna sesuai batas
        if (watt >= 1200) {
            circle.style.stroke = '#ef4444';
        } else if (watt >= 900) {
            circle.style.stroke = '#facc15';
        } else {
            circle.style.stroke = '#22c55e';
        }
    }

    // Jalankan realtime tiap 5 detik
    setInterval(fetchRealtimePower, 5000);
    fetchRealtimePower();
</script>



</html>
