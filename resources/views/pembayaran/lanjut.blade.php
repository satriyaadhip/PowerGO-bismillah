<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lanjut Pembayaran | PowerGO</title>
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
    
    <div class="container mx-auto px-4 py-6">
        <div class="max-w-2xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Konfirmasi Pembayaran</h1>
                <p class="text-gray-600">Silakan selesaikan pembayaran Anda</p>
            </div>

            <!-- Customer Info Card -->
            <div class="bg-[#eaeff4] rounded-3xl p-6 mb-4">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Detail Pelanggan</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">ID Pelanggan</p>
                        <p class="text-lg font-bold text-gray-900">{{ $pelangganId }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Nama Pelanggan</p>
                        <p class="text-lg font-bold text-gray-900">{{ $nama }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Jenis Pelanggan</p>
                        <p class="text-lg font-bold text-gray-900">{{ $jenis }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Summary Card -->
            <div class="bg-white rounded-3xl shadow-lg p-6 mb-4">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Ringkasan Pembayaran</h2>
                
                @if($billingType === 'prabayar')
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-4 border-b">
                            <span class="text-gray-600">Jenis Pembayaran</span>
                            <span class="font-semibold text-gray-900">{{ $billingTypeDisplay }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-4 border-b">
                            <span class="text-gray-600">Nominal Token</span>
                            <span class="text-xl font-bold text-gray-900">{{ $selectedAmount }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-4 border-b">
                            <span class="text-gray-600">Daya yang Didapat</span>
                            <span class="font-semibold text-gray-900">{{ $selectedKwh }} kWh</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Metode Pembayaran</span>
                            <span class="font-semibold text-purple-600">{{ $selectedPaymentMethod }}</span>
                        </div>
                    </div>
                @else
                    @if($billData)
                        <div class="space-y-4">
                            <div class="flex justify-between items-center pb-4 border-b">
                                <span class="text-gray-600">Jenis Pembayaran</span>
                                <span class="font-semibold text-gray-900">{{ $billingTypeDisplay }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b">
                                <span class="text-gray-600">Periode Tagihan</span>
                                <span class="font-semibold text-gray-900">{{ $billData['period'] }}</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b">
                                <span class="text-gray-600">Total kWh</span>
                                <span class="font-semibold text-gray-900">{{ number_format($billData['totalKwh'], 2) }} kWh</span>
                            </div>
                            <div class="flex justify-between items-center pb-4 border-b">
                                <span class="text-gray-600">Tarif per kWh</span>
                                <span class="font-semibold text-gray-900">Rp {{ number_format($billData['tarifPerKwh'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center pt-4 border-t-2">
                                <span class="text-xl font-bold text-gray-900">Total Tagihan</span>
                                <span class="text-2xl font-bold text-purple-600">{{ $selectedAmount }}</span>
                            </div>
                            <div class="flex justify-between items-center mt-4">
                                <span class="text-gray-600">Metode Pembayaran</span>
                                <span class="font-semibold text-purple-600">{{ $selectedPaymentMethod }}</span>
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Payment Instructions -->
            <div class="bg-white rounded-3xl shadow-lg p-6 mb-4">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Instruksi Pembayaran</h2>
                
                @if($selectedPaymentMethod === 'QRIS')
                    <div class="text-center mb-4">
                        <div class="bg-gray-100 rounded-2xl p-8 mb-4 flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-32 h-32 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                </svg>
                                <p class="text-gray-500 text-sm">QR Code akan muncul di sini</p>
                            </div>
                        </div>
                        <p class="text-gray-700 mb-2">Scan QR Code dengan aplikasi pembayaran Anda</p>
                        <p class="text-sm text-gray-500">Pembayaran akan diproses secara otomatis setelah scan</p>
                    </div>
                @elseif($selectedPaymentMethod === 'Virtual Account')
                    <div class="bg-gray-50 rounded-2xl p-6 mb-4">
                        <p class="text-sm text-gray-600 mb-2">Nomor Virtual Account:</p>
                        <p class="text-2xl font-bold text-gray-900 mb-4">8888 1234 5678 9012</p>
                        <p class="text-sm text-gray-600">Transfer sesuai nominal tagihan ke nomor Virtual Account di atas</p>
                    </div>
                @elseif(in_array($selectedPaymentMethod, ['Alfamart', 'Indomaret']))
                    <div class="bg-gray-50 rounded-2xl p-6 mb-4">
                        <p class="text-sm text-gray-600 mb-2">Kode Pembayaran:</p>
                        <p class="text-2xl font-bold text-gray-900 mb-4">PWR-{{ strtoupper(substr(md5($pelangganId . time()), 0, 8)) }}</p>
                        <p class="text-sm text-gray-600">Tunjukkan kode ini ke kasir {{ $selectedPaymentMethod }} untuk menyelesaikan pembayaran</p>
                    </div>
                @else
                    <div class="bg-gray-50 rounded-2xl p-6 mb-4">
                        <p class="text-gray-700 mb-2">Pembayaran melalui <strong>{{ $selectedPaymentMethod }}</strong></p>
                        <p class="text-sm text-gray-600">Ikuti instruksi yang muncul di aplikasi {{ $selectedPaymentMethod }} Anda</p>
                    </div>
                @endif

                @if($billingType === 'prabayar')
                <div class="mt-6 p-4 bg-green-50 rounded-xl border border-green-200">
                    <p class="text-sm text-green-800">
                        <strong>✓ Berhasil!</strong> Transaksi telah disimpan. <strong>{{ $selectedKwh }} kWh</strong> telah ditambahkan ke saldo Anda.
                    </p>
                </div>
                @else
                <div class="mt-6 p-4 bg-green-50 rounded-xl border border-green-200">
                    <p class="text-sm text-green-800">
                        <strong>✓ Berhasil!</strong> Transaksi pembayaran tagihan telah disimpan.
                    </p>
                </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('pembayaran') }}" 
                   class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-2xl p-4 text-center font-semibold transition-colors">
                    Kembali ke Pembayaran
                </a>
                <a href="{{ route('dashboard') }}" 
                   class="flex-1 bg-purple-600 hover:bg-purple-700 text-white rounded-2xl p-4 text-center font-semibold transition-colors">
                    Ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <x-bottom-navigation />
</body>

</html>

