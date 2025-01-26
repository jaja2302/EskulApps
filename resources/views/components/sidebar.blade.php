<aside class="fixed md:left-6 md:top-1/2 md:-translate-y-1/2 md:w-16 
            bottom-0 left-0 right-0 h-[60px] w-full md:rounded-full rounded-none
            sidebar flex md:flex-col items-center md:py-6
            justify-around md:justify-start shadow-lg">
    
    <nav class="flex md:flex-col items-center gap-6 w-full px-4 md:px-0 justify-around md:justify-start">
        <!-- Home -->
        <a href="{{ route('dashboard') }}" 
           class="nav-icon tooltip rounded-xl {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }} transition-all duration-200">
            <svg class="w-6 h-6 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="tooltip-text">Home</span>
        </a>

        @role('admin')
        <!-- Eskul -->
        <a href="{{ route('dashboard.eskul') }}" 
           class="nav-icon tooltip rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-all duration-200">
            <svg class="w-6 h-6 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span class="tooltip-text">Dashboard Eskul</span>
        </a>

        <!-- Manage Users -->   
        <a href="{{ route('manageusers') }}" 
           class="nav-icon tooltip rounded-xl text-white/70 hover:bg-white/10 hover:text-white transition-all duration-200">
            <svg class="w-6 h-6 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <span class="tooltip-text">Manage Users</span>
        </a>
        @endrole
    </nav>
</aside> 