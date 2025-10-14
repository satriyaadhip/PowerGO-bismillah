<div class="container sm:px-4">
    <div class="flex gap-2">
        <a href="/dashboard" 
           class="group relative flex items-center px-4 py-2 rounded-full transition-all font-semibold
           {{ request()->is('dashboard') ? 'bg-white shadow-sm' : 'bg-gray-300 text-gray-700' }}">
            <span class="absolute inset-0 rounded-full bg-white opacity-0 group-hover:opacity-100 group-hover:shadow-md transition-all -z-10"></span>
            <span class="{{ request()->routeIs('dashboard') ? 'font-bold' : 'group-hover:font-bold' }}">Home</span>
        </a>
        
        <a href="/dashboard/total-daya" 
           class="group relative flex items-center px-4 py-2 rounded-full transition-all font-semibold
           {{ request()->is('dashboard/total-daya') ? 'bg-white shadow-sm' : 'bg-gray-300 text-gray-700' }}">
            <span class="absolute inset-0 rounded-full bg-white opacity-0 group-hover:opacity-100 group-hover:shadow-md transition-all -z-10"></span>
            <span class="{{ request()->routeIs('dashboard.total-daya') ? 'font-bold' : 'group-hover:font-bold' }}">Total daya</span>
        </a>
        
        <a href="/dashboard/sisa-kwh" 
           class="group relative flex items-center px-4 py-2 rounded-full transition-all font-semibold
           {{ request()->is('dashboard/sisa-kwh') ? 'bg-white shadow-sm' : 'bg-gray-300 text-gray-700' }}">
            <span class="absolute inset-0 rounded-full bg-white opacity-0 group-hover:opacity-100 group-hover:shadow-md transition-all -z-10"></span>
            <span class="{{ request()->routeIs('dashboard.sisa-kwh') ? 'font-bold' : 'group-hover:font-bold' }}">Sisa kWh</span>
        </a>
    </div>
</div>