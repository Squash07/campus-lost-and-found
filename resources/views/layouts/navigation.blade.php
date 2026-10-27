@php
    $navLink = fn (bool $active) => 'px-3 py-2 rounded-lg text-sm font-semibold transition ' .
        ($active ? 'text-sky-700 bg-sky-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100');
@endphp

<nav x-data="{ open: false }" class="bg-white/95 backdrop-blur border-b border-slate-200 sticky top-0 z-50" aria-label="Main">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <a href="{{ route('items.index') }}" class="flex items-center gap-2.5">
                    <x-application-logo class="w-9 h-9" />
                    <span class="font-extrabold text-lg tracking-tight text-slate-900">Campus Lost&nbsp;&amp;&nbsp;Found</span>
                </a>

                <div class="hidden md:flex md:gap-1">
                    <a href="{{ route('items.index') }}" class="{{ $navLink(request()->routeIs('items.index')) }}" @if(request()->routeIs('items.index')) aria-current="page" @endif>Browse</a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="{{ $navLink(request()->routeIs('dashboard')) }}" @if(request()->routeIs('dashboard')) aria-current="page" @endif>
                            Dashboard
                            @if($toReview > 0)
                                <span class="ms-1 inline-flex items-center justify-center min-w-5 h-5 px-1.5 rounded-full bg-rose-600 text-white text-xs font-bold" title="{{ $toReview }} claim(s) waiting for your review">{{ $toReview }}</span>
                            @endif
                        </a>
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.categories.index') }}" class="{{ $navLink(request()->routeIs('admin.categories.*')) }}">Categories</a>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="hidden md:flex md:items-center md:gap-2">
                @auth
                    <a href="{{ route('items.create') }}" class="inline-flex items-center px-4 py-2 bg-sky-700 hover:bg-sky-800 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                        <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Post an item
                    </a>

                    <div class="relative" x-data="{ userMenuOpen: false }" @keydown.escape="userMenuOpen = false">
                        <button type="button" @click="userMenuOpen = !userMenuOpen" @click.outside="userMenuOpen = false"
                                :aria-expanded="userMenuOpen.toString()" aria-haspopup="true"
                                class="flex items-center gap-2 text-sm font-medium text-slate-700 hover:text-slate-900 px-2 py-1.5 rounded-lg hover:bg-slate-100 transition">
                            <span class="w-8 h-8 rounded-full bg-sky-100 text-sky-800 flex items-center justify-center font-bold text-xs" aria-hidden="true">
                                {{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <span class="font-semibold max-w-32 truncate">{{ Auth::user()->name }}</span>
                            @if(Auth::user()->isAdmin())
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">Admin</span>
                            @endif
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>

                        <div x-show="userMenuOpen" x-cloak x-transition.origin.top.right
                             class="absolute right-0 mt-2 w-56 rounded-xl bg-white shadow-lg ring-1 ring-slate-200 py-1 z-50">
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-xs text-slate-500">Signed in as</p>
                                <p class="text-sm font-semibold text-slate-900 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Dashboard</a>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Account settings</a>
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.categories.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Manage categories</a>
                            @endif
                            <div class="border-t border-slate-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-rose-600 hover:bg-rose-50">Log out</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-slate-700 hover:text-slate-900 px-4 py-2 text-sm font-semibold rounded-lg hover:bg-slate-100 transition">Log in</a>
                    <a href="{{ route('register') }}" class="bg-sky-700 hover:bg-sky-800 text-white px-4 py-2 text-sm font-semibold rounded-lg shadow-sm transition">Create account</a>
                @endauth
            </div>

            <div class="flex items-center md:hidden">
                <button type="button" @click="open = !open" :aria-expanded="open.toString()" aria-controls="mobile-menu"
                        class="p-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100">
                    <span class="sr-only" x-text="open ? 'Close menu' : 'Open menu'">Open menu</span>
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="open" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div id="mobile-menu" x-show="open" x-cloak class="md:hidden border-t border-slate-200 bg-white px-4 pt-2 pb-4 space-y-1">
        <a href="{{ route('items.index') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:bg-slate-100">Browse</a>
        @auth
            <a href="{{ route('items.create') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-sky-700 hover:bg-sky-50">Post an item</a>
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:bg-slate-100">Dashboard</a>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.categories.index') }}" class="block px-3 py-2 rounded-lg text-base font-semibold text-slate-700 hover:bg-slate-100">Manage categories</a>
            @endif
            <div class="border-t border-slate-200 pt-2 mt-2">
                <p class="px-3 py-1 text-sm text-slate-500">Signed in as <span class="font-semibold text-slate-900">{{ Auth::user()->name }}</span></p>
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-lg text-sm text-slate-700 hover:bg-slate-100">Account settings</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-sm font-semibold text-rose-600 hover:bg-rose-50">Log out</button>
                </form>
            </div>
        @else
            <div class="border-t border-slate-200 pt-3 mt-2 grid grid-cols-2 gap-2">
                <a href="{{ route('login') }}" class="text-center py-2 text-sm font-semibold text-slate-700 rounded-lg border border-slate-200">Log in</a>
                <a href="{{ route('register') }}" class="text-center py-2 text-sm font-semibold bg-sky-700 text-white rounded-lg">Create account</a>
            </div>
        @endauth
    </div>
</nav>
