@props(['type', 'size' => 'sm'])

{{-- The Lost / Found tag: the one bold colour signal across the site --}}
<span {{ $attributes->merge(['class' =>
    'inline-flex items-center gap-1 font-bold rounded-md shadow-sm text-white ' .
    ($type === 'lost' ? 'bg-rose-600 ' : 'bg-emerald-600 ') .
    ($size === 'lg' ? 'px-3 py-1 text-sm' : 'px-2 py-0.5 text-xs')
]) }}>
    @if($type === 'lost')
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01"/></svg>
        Lost
    @else
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        Found
    @endif
</span>
