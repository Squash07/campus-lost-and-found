@props(['item'])

<article class="relative bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-slate-300 transition flex flex-col overflow-hidden group {{ $item->status === 'returned' ? 'opacity-80' : '' }}">
    <div class="relative h-44 overflow-hidden bg-slate-100">
        <x-item-photo :item="$item" class="group-hover:scale-[1.03] transition-transform duration-300" />

        <div class="absolute top-3 left-3">
            <x-type-badge :type="$item->type" />
        </div>
        <div class="absolute top-3 right-3">
            <x-status-badge :status="$item->status" class="bg-white/95" />
        </div>
    </div>

    <div class="p-5 flex-1 flex flex-col">
        <p class="text-xs font-medium text-sky-700 mb-1">{{ $item->category->name }}</p>
        <h3 class="font-bold text-slate-900 leading-snug line-clamp-2">
            {{-- Stretched link: the whole card is clickable --}}
            <a href="{{ route('items.show', $item) }}" class="after:absolute after:inset-0 focus:outline-none focus-visible:after:ring-2 focus-visible:after:ring-sky-500 focus-visible:after:rounded-2xl">
                {{ $item->title }}
            </a>
        </h3>
        <p class="mt-2 text-sm text-slate-600 line-clamp-2">{{ $item->description }}</p>

        <dl class="mt-auto pt-4 space-y-1.5 text-xs text-slate-500">
            <div class="flex items-center gap-1.5">
                <dt class="sr-only">Location</dt>
                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <dd class="truncate">{{ $item->location }}</dd>
            </div>
            <div class="flex items-center gap-1.5">
                <dt class="sr-only">Date</dt>
                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <dd>
                    {{ $item->type === 'lost' ? 'Lost' : 'Found' }}
                    <time datetime="{{ $item->event_date->toDateString() }}" title="{{ $item->event_date->format('l, j F Y') }}">
                        {{ $item->event_date->isToday() ? 'today' : ($item->event_date->isYesterday() ? 'yesterday' : $item->event_date->diffForHumans(['parts' => 1])) }}
                    </time>
                    by {{ $item->user->name }}
                </dd>
            </div>
        </dl>
    </div>
</article>
