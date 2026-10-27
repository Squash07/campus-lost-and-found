<x-app-layout>
    <x-slot name="title">Browse items</x-slot>

    @php
        // Build a feed URL that keeps the current filters but changes some of them.
        $feedUrl = fn (array $changes) => route('items.index', array_filter(
            array_merge(request()->only(['q', 'type', 'category', 'status']), $changes),
            fn ($v) => filled($v)
        ));
        $activeCategory = $categories->firstWhere('id', (int) request('category'));
        $hasFilters = request()->anyFilled(['q', 'type', 'category', 'status']);
    @endphp

    <!-- Hero: searching is the main job of this page -->
    <section class="bg-sky-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
            <div class="max-w-3xl">
                <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight leading-tight">
                    Lost something on campus? Someone may have found it.
                </h1>
                <p class="mt-4 text-sky-100 text-base sm:text-lg max-w-2xl">
                    Search items students have reported around campus, or post what you lost or found so it can get back to its owner.
                </p>

                <form method="GET" action="{{ route('items.index') }}" class="mt-8 flex flex-col sm:flex-row gap-2" role="search">
                    @foreach(request()->only(['type', 'category', 'status']) as $key => $value)
                        @if(filled($value)) <input type="hidden" name="{{ $key }}" value="{{ $value }}"> @endif
                    @endforeach
                    <label for="q" class="sr-only">Search items</label>
                    <div class="relative flex-1">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="search" id="q" name="q" value="{{ request('q') }}"
                               placeholder="Try &quot;wallet&quot;, &quot;AirPods&quot; or &quot;library&quot;"
                               class="w-full pl-12 pr-4 py-3.5 rounded-xl border-0 text-slate-900 placeholder:text-slate-400 text-base shadow-lg focus:ring-4 focus:ring-sky-400/50">
                    </div>
                    <button type="submit" class="px-6 py-3.5 rounded-xl bg-white text-sky-900 font-bold hover:bg-sky-50 shadow-lg transition">Search</button>
                </form>

                <div class="mt-6 flex flex-wrap items-center gap-3 text-sm">
                    <span class="text-sky-200">Or report an item:</span>
                    <a href="{{ route('items.create', ['type' => 'lost']) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 font-semibold transition">I lost something</a>
                    <a href="{{ route('items.create', ['type' => 'found']) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 font-semibold transition">I found something</a>
                </div>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Type tabs + filters (F6) -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-4">
            <nav class="inline-flex p-1 rounded-xl bg-slate-200/70 self-start" aria-label="Item type">
                @foreach(['' => 'All items', 'lost' => 'Lost', 'found' => 'Found'] as $value => $label)
                    @php $active = (string) request('type') === $value; @endphp
                    <a href="{{ $feedUrl(['type' => $value]) }}"
                       @if($active) aria-current="page" @endif
                       class="px-4 py-1.5 rounded-lg text-sm font-semibold transition {{ $active ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </nav>

            <form method="GET" action="{{ route('items.index') }}" class="flex flex-wrap gap-2" x-data>
                @foreach(request()->only(['q', 'type']) as $key => $value)
                    @if(filled($value)) <input type="hidden" name="{{ $key }}" value="{{ $value }}"> @endif
                @endforeach
                <label for="category" class="sr-only">Category</label>
                <select id="category" name="category" @change="$el.form.submit()"
                        class="py-2 pl-3 pr-9 text-sm rounded-xl border-slate-300 bg-white focus:border-sky-500 focus:ring-sky-500">
                    <option value="">All categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <label for="status" class="sr-only">Status</label>
                <select id="status" name="status" @change="$el.form.submit()"
                        class="py-2 pl-3 pr-9 text-sm rounded-xl border-slate-300 bg-white focus:border-sky-500 focus:ring-sky-500">
                    <option value="">Any status</option>
                    <option value="open" @selected(request('status') === 'open')>Open</option>
                    <option value="claimed" @selected(request('status') === 'claimed')>Claimed</option>
                    <option value="returned" @selected(request('status') === 'returned')>Returned</option>
                </select>
                <noscript><button type="submit" class="px-4 py-2 rounded-xl bg-sky-700 text-white text-sm font-semibold">Apply</button></noscript>
            </form>
        </div>

        <!-- Result count + removable filter chips -->
        <div class="flex flex-wrap items-center gap-2 mb-6 text-sm">
            <p class="text-slate-600 me-2">
                <span class="font-semibold text-slate-900">{{ $items->total() }}</span> {{ Str::plural('item', $items->total()) }}
            </p>
            @if($hasFilters)
                @php
                    $chips = array_filter([
                        'q' => request('q') ? '"' . request('q') . '"' : null,
                        'type' => request('type') ? ucfirst(request('type')) : null,
                        'category' => $activeCategory?->name,
                        'status' => request('status') ? ucfirst(request('status')) : null,
                    ]);
                @endphp
                @foreach($chips as $key => $label)
                    <a href="{{ $feedUrl([$key => null]) }}" class="inline-flex items-center gap-1 pl-3 pr-2 py-1 rounded-full bg-sky-50 text-sky-800 ring-1 ring-inset ring-sky-200 hover:bg-sky-100 transition">
                        {{ $label }}
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span class="sr-only">Remove filter</span>
                    </a>
                @endforeach
                <a href="{{ route('items.index') }}" class="ms-1 font-semibold text-slate-500 hover:text-slate-900 underline-offset-2 hover:underline">Clear all</a>
            @endif
        </div>

        <!-- Items Feed Grid (F2) -->
        @if($items->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($items as $item)
                    <x-item-card :item="$item" />
                @endforeach
            </div>

            <div class="mt-10">
                {{ $items->links() }}
            </div>
        @else
            <div class="bg-white rounded-2xl border border-dashed border-slate-300 p-12 text-center max-w-xl mx-auto">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                @if($hasFilters)
                    <h2 class="text-lg font-bold text-slate-900">No items match these filters</h2>
                    <p class="mt-1 text-sm text-slate-500">Try a shorter search word or remove a filter. If you lost something, post it so finders can reach you.</p>
                @else
                    <h2 class="text-lg font-bold text-slate-900">No items reported yet</h2>
                    <p class="mt-1 text-sm text-slate-500">Be the first to post something you lost or found on campus.</p>
                @endif
                <div class="mt-6 flex justify-center gap-3">
                    @if($hasFilters)
                        <a href="{{ route('items.index') }}" class="px-4 py-2 rounded-xl text-sm font-semibold border border-slate-200 text-slate-700 hover:bg-slate-50 transition">Clear filters</a>
                    @endif
                    <a href="{{ route('items.create') }}" class="px-4 py-2 rounded-xl text-sm font-semibold bg-sky-700 text-white hover:bg-sky-800 transition">Post an item</a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
