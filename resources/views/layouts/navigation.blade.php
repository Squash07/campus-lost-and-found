<nav x-data="{ open: false }" class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Brand & Navigation Links -->
            <div class="flex items-center space-x-8">
                <!-- Brand Logo -->
                <a href="{{ route('items.index') }}" class="flex items-center space-x-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-sky-600 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-sky-200 group-hover:scale-105 transition-transform duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                        </svg>
                    </div>
                    <div>
                        <span class="font-extrabold text-lg tracking-tight text-slate-900 group-hover:text-sky-600 transition">Campus</span>
                        <span class="font-bold text-lg text-sky-600">Lost&Found</span>
                    </div>
                </a>

                <!-- Desktop Nav Links -->
                <div class="hidden md:flex md:space-x-1 lg:space-x-2">
                    <a href="{{ route('items.index') }}"
                       class="px-3 py-2 rounded-lg text-sm font-semibold transition {{ request()->routeIs('items.index') ? 'text-sky-600 bg-sky-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        Browse Feed
                    </a>

                    @auth
                        <a href="{{ route('items.create') }}"
                           class="px-3 py-2 rounded-lg text-sm font-semibold transition {{ request()->routeIs('items.create') ? 'text-sky-600 bg-sky-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            + Post Item
                        </a>

                        <a href="{{ route('dashboard') }}"
                           class="px-3 py-2 rounded-lg text-sm font-semibold transition {{ request()->routeIs('dashboard') ? 'text-sky-600 bg-sky-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            My Dashboard
                        </a>

                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.categories.index') }}"
                               class="px-3 py-2 rounded-lg text-sm font-semibold transition {{ request()->routeIs('admin.categories.*') ? 'text-indigo-600 bg-indigo-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                <span class="inline-flex items-center">
                                    Categories
                                    <span class="ms-1.5 px-1.5 py-0.5 text-xs font-bold rounded bg-indigo-100 text-indigo-700">Admin</span>
                                </span>
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Right: Auth Actions -->
            <div class="hidden md:flex md:items-center md:space-x-3">
                @auth
                    <a href="{{ route('items.create') }}" class="inline-flex items-center px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold rounded-lg shadow-sm hover:shadow transition">
                        <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Report Item
                    </a>

                    <!-- User Dropdown Menu -->
                    <div class="relative ms-2" x-data="{ userMenuOpen: false }">
                        <button @click="userMenuOpen = !userMenuOpen"
                                @click.away="userMenuOpen = false"
                                class="flex items-center space-x-2 text-sm font-medium text-slate-700 hover:text-slate-900 px-3 py-2 rounded-lg hover:bg-slate-100 transition">
                            <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-xs">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <span class="font-semibold">{{ Auth::user()->name }}</span>
                            @if(Auth::user()->isAdmin())
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">Admin</span>
                            @endif
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="userMenuOpen"
                             x-cloak
                             class="absolute right-0 mt-2 w-48 rounded-xl bg-white shadow-lg ring-1 ring-black ring-opacity-5 py-1 z-50">
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-xs text-slate-500">Signed in as</p>
                                <p class="text-sm font-bold text-slate-900 truncate">{{ Auth::user()->email }}</p>
                            </div>

                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 font-medium">Dashboard</a>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 font-medium">Account Settings</a>

                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.categories.index') }}" class="block px-4 py-2 text-sm text-indigo-600 hover:bg-indigo-50 font-medium">Manage Categories</a>
                            @endif

                            <div class="border-t border-slate-100 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 font-medium">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-slate-600 hover:text-slate-900 px-4 py-2 text-sm font-semibold rounded-lg hover:bg-slate-100 transition">
                        Log In
                    </a>
                    <a href="{{ route('register') }}" class="bg-sky-600 hover:bg-sky-700 text-white px-4 py-2 text-sm font-semibold rounded-lg shadow-sm hover:shadow transition">
                        Register
                    </a>
                @endauth
            </div>

            <!-- Mobile Hamburger Button -->
            <div class="flex items-center md:hidden">
                <button @click="open = !open" class="p-2 rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-100">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="open" x-cloak class="md:hidden border-b border-slate-200 bg-white px-4 pt-2 pb-4 space-y-2">
        <a href="{{ route('items.index') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:bg-slate-100">Browse Feed</a>
        @auth
            <a href="{{ route('items.create') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:bg-slate-100">+ Post Item</a>
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:bg-slate-100">My Dashboard</a>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.categories.index') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-indigo-700 hover:bg-indigo-50">Manage Categories</a>
            @endif
            <div class="border-t border-slate-200 pt-2">
                <div class="px-3 py-1 text-sm font-semibold text-slate-900">{{ Auth::user()->name }} ({{ Auth::user()->role }})</div>
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-lg text-sm text-slate-600 hover:bg-slate-100">Settings</a>
                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-sm font-semibold text-rose-600 hover:bg-rose-50">Log Out</button>
                </form>
            </div>
        @else
            <div class="border-t border-slate-200 pt-2 flex flex-col space-y-2">
                <a href="{{ route('login') }}" class="text-center py-2 text-sm font-semibold text-slate-700 rounded-lg hover:bg-slate-100">Log In</a>
                <a href="{{ route('register') }}" class="text-center py-2 text-sm font-semibold bg-sky-600 text-white rounded-lg shadow-sm">Register</a>
            </div>
        @endauth
    </div>
</nav>
