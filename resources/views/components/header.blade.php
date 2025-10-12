<div class="sticky top-0 z-50 bg-[#E1DFEC]">
    <header class="p-6">
        <div class="mx-auto flex justify-between items-center">
            <!-- Left: Logo and Desktop Navigation -->
            <div class="flex items-center gap-4 px-4">
                <a href="/">
                    <h1 class="text-2xl font-medium">Power<span class="font-black">GO</span></h1>
                </a>
                <!-- Desktop Navigation (next to logo) -->
                <nav class="hidden sm:block">
                    <ul class="flex">
                        <li>
                            <a href="/dashboard"
                                class="ml-4 group relative flex items-center p-4 rounded-[30px] transition-all font-semibold
                   {{ request()->is('dashboard') ? 'font-bold shadow-md bg-white' : '' }}">
                                <span
                                    class="absolute inset-0 rounded-[30px] bg-white opacity-0 group-hover:opacity-100 group-hover:shadow-md transition-all -z-10"></span>
                                <img src="{{ asset('images/button_home.png') }}" alt="Dashboard" class="w-5 h-5 mr-2">
                                <span
                                    class="{{ request()->is('dashboard') ? 'font-bold' : 'group-hover:font-bold' }}">Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="/pembayaran"
                                class="group relative flex items-center p-4 rounded-[30px] transition-all font-semibold
                   {{ request()->is('pembayaran') ? 'font-bold shadow-md bg-white' : '' }}">
                                <span
                                    class="absolute inset-0 rounded-[30px] bg-white opacity-0 group-hover:opacity-100 group-hover:shadow-md transition-all -z-10"></span>
                                <img src="{{ asset('images/button_pembayaran.png') }}" alt="Pembayaran"
                                    class="w-5 h-5 mr-2">
                                <span
                                    class="{{ request()->is('pembayaran') ? 'font-bold' : 'group-hover:font-bold' }}">Pembayaran</span>
                            </a>
                        </li>
                        <li>
                            <a href="/riwayat"
                                class="group relative flex items-center p-4 rounded-[30px] transition-all font-semibold
                   {{ request()->is('riwayat') ? 'font-bold shadow-md bg-white' : '' }}">
                                <span
                                    class="absolute inset-0 rounded-[30px] bg-white opacity-0 group-hover:opacity-100 group-hover:shadow-md transition-all -z-10"></span>
                                <img src="{{ asset('images/button_transaksi.png') }}" alt="Riwayat"
                                    class="w-5 h-5 mr-2">
                                <span
                                    class="{{ request()->is('riwayat') ? 'font-bold' : 'group-hover:font-bold' }}">Riwayat</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            <!-- Right: Profile -->
            <div class="flex items-center gap-2">
                <div>profile</div>
            </div>
        </div>
    </header>
</div>
