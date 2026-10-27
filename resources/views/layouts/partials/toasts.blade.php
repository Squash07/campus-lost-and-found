{{-- Flash messages as toasts. Success fades after 5 s; errors stay until closed. --}}
@php
    $toasts = collect([
        ['type' => 'success', 'text' => session('success')],
        ['type' => 'error', 'text' => session('error')],
        ['type' => 'success', 'text' => session('status') === 'profile-updated' ? 'Profile saved.' : null],
        ['type' => 'success', 'text' => session('status') === 'password-updated' ? 'Password changed.' : null],
    ])->filter(fn ($t) => filled($t['text']))->values();
@endphp

<div aria-live="polite" class="fixed z-[90] top-20 right-4 left-4 sm:left-auto sm:w-96 space-y-3 pointer-events-none">
    @foreach($toasts as $toast)
        <div x-data="{ show: false }"
             x-init="$nextTick(() => show = true); @if($toast['type'] === 'success') setTimeout(() => show = false, 5000) @endif"
             x-show="show"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-cloak
             role="{{ $toast['type'] === 'error' ? 'alert' : 'status' }}"
             class="pointer-events-auto flex items-start gap-3 p-4 rounded-xl bg-white shadow-lg ring-1 {{ $toast['type'] === 'error' ? 'ring-rose-200' : 'ring-emerald-200' }}">
            @if($toast['type'] === 'error')
                <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            @else
                <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            @endif
            <p class="flex-1 text-sm font-medium text-slate-800">{{ $toast['text'] }}</p>
            <button type="button" @click="show = false" class="text-slate-400 hover:text-slate-700 -m-1 p-1 rounded" aria-label="Dismiss message">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endforeach
</div>
