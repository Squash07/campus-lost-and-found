@props(['status'])

@php
    $styles = [
        'open' => ['bg-emerald-50 text-emerald-800 ring-emerald-200', 'bg-emerald-500', 'Open'],
        'claimed' => ['bg-amber-50 text-amber-800 ring-amber-200', 'bg-amber-500', 'Claimed'],
        'returned' => ['bg-slate-100 text-slate-600 ring-slate-200', 'bg-slate-400', 'Returned'],
        'pending' => ['bg-amber-50 text-amber-800 ring-amber-200', 'bg-amber-500', 'Pending'],
        'approved' => ['bg-emerald-50 text-emerald-800 ring-emerald-200', 'bg-emerald-500', 'Approved'],
        'rejected' => ['bg-rose-50 text-rose-700 ring-rose-200', 'bg-rose-500', 'Rejected'],
    ];
    [$classes, $dot, $label] = $styles[$status] ?? $styles['open'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold ring-1 ring-inset $classes"]) }}>
    <span class="w-1.5 h-1.5 rounded-full {{ $dot }}" aria-hidden="true"></span>
    {{ $label }}
</span>
