@props(['current' => null])

<nav class="fixed bottom-0 left-0 right-0 bg-white border-t shadow-lg sm:hidden z-50">
    <ul class="flex justify-around items-center py-2">
        <li>
            <a href="/dashboard"
                class="flex flex-col items-center text-xs {{ request()->is('dashboard') ? 'font-bold text-black' : 'text-gray-500' }}">
                <img src="{{ asset('images/button_home.png') }}" alt="Dashboard" class="w-6 h-6 mb-1">
                Dashboard
            </a>
        </li>
        <li>
            <a href="/pembayaran"
                class="flex flex-col items-center text-xs {{ request()->is('pembayaran') ? 'font-bold text-black' : 'text-gray-500' }}">
                <img src="{{ asset('images/button_pembayaran.png') }}" alt="Pembayaran" class="w-6 h-6 mb-1">
                Pembayaran
            </a>
        </li>
        <li>
            <a href="/riwayat"
                class="flex flex-col items-center text-xs {{ request()->is('riwayat') ? 'font-bold text-black' : 'text-gray-500' }}">
                <img src="{{ asset('images/button_transaksi.png') }}" alt="Riwayat" class="w-6 h-6 mb-1">
                Riwayat
            </a>
        </li>
    </ul>
</nav>


