    <div class="container mx-auto flex flex-row gap-2">
        <a href="/dashboard" 
           class="group relative flex items-center px-4 py-2 rounded-full transition-all font-semibold
           {{ request()->is('dashboard') ? 'bg-white shadow-sm' : 'bg-gray-300 text-gray-700' }}">
            <span class="absolute inset-0 rounded-full bg-white opacity-0 group-hover:opacity-100 group-hover:shadow-md transition-all -z-10"></span>
            <span class="{{ request()->routeIs('dashboard') ? 'font-bold' : 'group-hover:font-bold' }}">Home</span>
        </a>
        
        <a href="/dashboard/total_daya" 
           class="group relative flex items-center px-4 py-2 rounded-full transition-all font-semibold
           {{ request()->is('dashboard/total_daya') ? 'bg-white shadow-sm' : 'bg-gray-300 text-gray-700' }}">
            <span class="absolute inset-0 rounded-full bg-white opacity-0 group-hover:opacity-100 group-hover:shadow-md transition-all -z-10"></span>
            <span class="{{ request()->routeIs('dashboard.total_daya') ? 'font-bold' : 'group-hover:font-bold' }}">Total daya</span>
        </a>
        
        <a href="/dashboard/sisa_kwh" 
           class="group relative flex items-center px-4 py-2 rounded-full transition-all font-semibold
           {{ request()->is('dashboard/sisa_kwh') ? 'bg-white shadow-sm' : 'bg-gray-300 text-gray-700' }}">
            <span class="absolute inset-0 rounded-full bg-white opacity-0 group-hover:opacity-100 group-hover:shadow-md transition-all -z-10"></span>
            <span class="{{ request()->routeIs('dashboard.sisa_kwh') ? 'font-bold' : 'group-hover:font-bold' }}">Sisa kWh</span>
        </a>
    </div>
