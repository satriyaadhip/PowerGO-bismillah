@php
    use Illuminate\Support\Facades\Auth;
@endphp

<div class="sticky top-0 z-50 bg-[#E1DFEC]">
    <header class="p-6">
        <div class="mx-auto flex justify-between items-center">

            <!-- Left: Logo + Nav -->
            <div class="flex items-center gap-4 px-4">

                <!-- LOGO -->
                <a href="/">
                    <h1 class="text-2xl font-medium font-exo">
                        Power<span class="font-black">GO</span>
                    </h1>
                </a>

                <!-- Desktop Navigation -->
                <nav class="hidden sm:block">
                    <ul class="flex">

                        <!-- DASHBOARD -->
                        <li>
                            <a href="/dashboard"
                                class="ml-4 group relative flex items-center p-4 rounded-[30px] transition-all font-semibold
                                {{ request()->is('dashboard') ? 'font-bold shadow-md bg-white' : '' }}">
                                <span
                                    class="absolute inset-0 rounded-[30px] bg-white opacity-0 group-hover:opacity-100 group-hover:shadow-md transition-all -z-10"></span>

                                <img src="{{ asset('images/button_home.png') }}"
                                     alt="Dashboard"
                                     class="w-5 h-5 mr-2">

                                <span class="{{ request()->is('dashboard') ? 'font-bold' : 'group-hover:font-bold' }}">
                                    Dashboard
                                </span>
                            </a>
                        </li>

                        <!-- PEMBAYARAN -->
                        <li>
                            <a href="/pembayaran"
                                class="group relative flex items-center p-4 rounded-[30px] transition-all font-semibold
                                {{ request()->is('pembayaran') ? 'font-bold shadow-md bg-white' : '' }}">
                                <span
                                    class="absolute inset-0 rounded-[30px] bg-white opacity-0 group-hover:opacity-100 group-hover:shadow-md transition-all -z-10"></span>

                                <img src="{{ asset('images/button_pembayaran.png') }}"
                                     alt="Pembayaran"
                                     class="w-5 h-5 mr-2">

                                <span class="{{ request()->is('pembayaran') ? 'font-bold' : 'group-hover:font-bold' }}">
                                    Pembayaran
                                </span>
                            </a>
                        </li>

                        <!-- RIWAYAT -->
                        <li>
                            <a href="/riwayat"
                                class="group relative flex items-center p-4 rounded-[30px] transition-all font-semibold
                                {{ request()->is('riwayat') ? 'font-bold shadow-md bg-white' : '' }}">
                                <span
                                    class="absolute inset-0 rounded-[30px] bg-white opacity-0 group-hover:opacity-100 group-hover:shadow-md transition-all -z-10"></span>

                                <img src="{{ asset('images/button_transaksi.png') }}"
                                     alt="Riwayat"
                                     class="w-5 h-5 mr-2">

                                <span class="{{ request()->is('riwayat') ? 'font-bold' : 'group-hover:font-bold' }}">
                                    Riwayat
                                </span>
                            </a>
                        </li>

                    </ul>
                </nav>
            </div>

            <!-- Right Section: Profile + Logout -->
            <div class="flex items-center gap-4 pr-4">

                <!-- Display logged-in user's name -->
                <div class="text-sm font-semibold">
                    {{ Auth::user()->name }}
                </div>

                <!-- Logout button -->
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button
                        class="px-4 py-2 rounded-full bg-red-500 text-white font-semibold text-sm hover:bg-red-600 transition">
                        Logout
                    </button>
                </form>

            </div>

        </div>
    </header>
</div>
