@props(['item', 'class' => ''])

{{-- Item photo, or a category placeholder when no photo was uploaded --}}
@if($item->photo_path)
    <img src="{{ asset('uploads/' . $item->photo_path) }}"
         alt="Photo of {{ $item->title }}"
         loading="lazy"
         class="w-full h-full object-cover {{ $class }}">
@else
    <div class="w-full h-full flex flex-col items-center justify-center gap-2 bg-slate-100 text-slate-400 {{ $class }}" role="img" aria-label="No photo for {{ $item->title }}">
        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <span class="text-xs font-medium">{{ $item->category->name }}</span>
    </div>
@endif
