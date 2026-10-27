<x-app-layout>
    <!-- Hero Banner -->
    <div class="bg-gradient-to-r from-sky-700 via-sky-800 to-indigo-900 text-white py-12 px-4 sm:px-6 lg:px-8 shadow-inner">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="max-w-2xl">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-sky-500/30 text-sky-200 border border-sky-400/30 mb-3">
                    Campus Community Board
                </span>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-white">
                    Lost Something? Or Found An Item On Campus?
                </h1>
                <p class="mt-3 text-slate-200 text-base leading-relaxed">
                    A centralized board for campus students and faculty to report lost or found belongings, submit ownership claims, and reconnect items with their rightful owners.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 shrink-0">
                <a href="{{ route('items.create') }}?type=found" class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm shadow-lg shadow-emerald-900/30 transition transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    I Found An Item
                </a>
                <a href="{{ route('items.create') }}?type=lost" class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-white hover:bg-slate-100 text-slate-800 font-bold text-sm shadow-lg shadow-black/10 transition transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5 me-2 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    I Lost Something
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Search and Filter Bar (F6) -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm mb-8">
            <form method="GET" action="{{ route('items.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <!-- Keyword Search -->
                <div class="lg:col-span-2">
                    <label for="q" class="block text-xs font-bold text-slate-600 mb-1">Keyword Search</label>
                    <div class="relative">
                        <input type="text"
                               id="q"
                               name="q"
                               value="{{ request('q') }}"
                               placeholder="Search title, description, or location..."
                               class="w-full pl-9 pr-4 py-2 text-sm rounded-xl border-slate-200 focus:border-sky-500 focus:ring-sky-500 bg-slate-50 focus:bg-white transition">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Type Filter -->
                <div>
                    <label for="type" class="block text-xs font-bold text-slate-600 mb-1">Item Type</label>
                    <select id="type" name="type" class="w-full py-2 px-3 text-sm rounded-xl border-slate-200 focus:border-sky-500 focus:ring-sky-500 bg-slate-50 focus:bg-white transition">
                        <option value="">All Types (Lost & Found)</option>
                        <option value="lost" {{ request('type') === 'lost' ? 'selected' : '' }}>Lost Reports</option>
                        <option value="found" {{ request('type') === 'found' ? 'selected' : '' }}>Found Items</option>
                    </select>
                </div>

                <!-- Category Filter -->
                <div>
                    <label for="category" class="block text-xs font-bold text-slate-600 mb-1">Category</label>
                    <select id="category" name="category" class="w-full py-2 px-3 text-sm rounded-xl border-slate-200 focus:border-sky-500 focus:ring-sky-500 bg-slate-50 focus:bg-white transition">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter & Submit Buttons -->
                <div>
                    <label for="status" class="block text-xs font-bold text-slate-600 mb-1">Status</label>
                    <div class="flex gap-2">
                        <select id="status" name="status" class="w-full py-2 px-3 text-sm rounded-xl border-slate-200 focus:border-sky-500 focus:ring-sky-500 bg-slate-50 focus:bg-white transition">
                            <option value="">All Statuses</option>
                            <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="claimed" {{ request('status') === 'claimed' ? 'selected' : '' }}>Claimed</option>
                            <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Returned</option>
                        </select>
                        <button type="submit" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-sm font-bold shadow-sm transition">
                            Filter
                        </button>
                    </div>
                </div>
            </form>

            @if(request()->anyFilled(['q', 'type', 'category', 'status']))
                <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <div>
                        Active filters:
                        @if(request('q')) <span class="font-semibold text-slate-700">"{{ request('q') }}"</span> @endif
                        @if(request('type')) <span class="font-semibold text-slate-700">Type: {{ ucfirst(request('type')) }}</span> @endif
                        @if(request('status')) <span class="font-semibold text-slate-700">Status: {{ ucfirst(request('status')) }}</span> @endif
                    </div>
                    <a href="{{ route('items.index') }}" class="text-sky-600 hover:text-sky-800 font-bold">Clear Filters</a>
                </div>
            @endif
        </div>

        <!-- Section Header with Count -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Recent Campus Items</h2>
                <p class="text-sm text-slate-500">Showing {{ $items->firstItem() ?? 0 }} to {{ $items->lastItem() ?? 0 }} of {{ $items->total() }} items</p>
            </div>
            <div class="flex items-center gap-2 text-xs">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-semibold border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Open
                </span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 font-semibold border border-amber-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Claimed
                </span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 font-semibold border border-slate-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Returned
                </span>
            </div>
        </div>

        <!-- Items Feed Grid (F2) -->
        @if($items->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($items as $item)
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col overflow-hidden group">
                        <!-- Image or Placeholder Banner -->
                        <div class="h-44 bg-slate-100 relative overflow-hidden flex items-center justify-center">
                            @if($item->photo_path)
                                <img src="{{ asset('uploads/' . $item->photo_path) }}"
                                     alt="{{ $item->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 text-slate-400 p-4 text-center">
                                    <svg class="w-12 h-12 mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">{{ $item->category->name }}</span>
                                </div>
                            @endif

                            <!-- Floating Badges -->
                            <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                                @if($item->type === 'lost')
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-rose-600 text-white shadow-sm">
                                        Lost
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-emerald-600 text-white shadow-sm">
                                        Found
                                    </span>
                                @endif

                                <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-white/90 backdrop-blur-sm text-slate-800 shadow-sm border border-slate-200">
                                    {{ $item->category->name }}
                                </span>
                            </div>

                            <div class="absolute top-3 right-3">
                                @if($item->status === 'open')
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 shadow-sm">
                                        Open
                                    </span>
                                @elseif($item->status === 'claimed')
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300 shadow-sm">
                                        Claimed
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-200 text-slate-700 border border-slate-300 shadow-sm">
                                        Returned
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="font-bold text-slate-900 text-lg leading-snug group-hover:text-sky-600 transition line-clamp-1 mb-2">
                                    <a href="{{ route('items.show', $item) }}">
                                        {{ $item->title }}
                                    </a>
                                </h3>

                                <p class="text-slate-600 text-sm line-clamp-2 mb-4 leading-relaxed">
                                    {{ $item->description }}
                                </p>
                            </div>

                            <div class="pt-4 border-t border-slate-100 space-y-2 text-xs text-slate-500">
                                <div class="flex items-center justify-between">
                                    <span class="flex items-center gap-1.5 truncate">
                                        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span class="truncate">{{ $item->location }}</span>
                                    </span>

                                    <span class="flex items-center gap-1 text-slate-400 shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $item->event_date->format('M d, Y') }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between pt-1">
                                    <span class="text-slate-400">By <strong class="text-slate-600">{{ $item->user->name }}</strong></span>
                                    <a href="{{ route('items.show', $item) }}" class="font-bold text-sky-600 hover:text-sky-800 text-xs flex items-center">
                                        View Details &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $items->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center max-w-lg mx-auto shadow-sm">
                <div class="w-16 h-16 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-1">No items found</h3>
                <p class="text-sm text-slate-500 mb-6">We couldn't find any lost or found items matching your current filters.</p>
                <div class="flex justify-center gap-3">
                    <a href="{{ route('items.index') }}" class="px-4 py-2 rounded-xl text-sm font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 transition">
                        Reset Filters
                    </a>
                    <a href="{{ route('items.create') }}" class="px-4 py-2 rounded-xl text-sm font-semibold bg-sky-600 text-white hover:bg-sky-700 transition">
                        Post New Item
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
