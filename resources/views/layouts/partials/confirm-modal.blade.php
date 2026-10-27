{{--
    One modal for every destructive or important action.
    Usage: <form ... data-confirm="Delete this item?" data-confirm-body="..." data-confirm-action="Delete" data-confirm-tone="danger|success">
--}}
<div x-data
     x-show="$store.confirm.open"
     x-cloak
     @keydown.escape.window="$store.confirm.cancel()"
     class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-4"
     role="dialog" aria-modal="true" aria-labelledby="confirm-title">
    <div x-show="$store.confirm.open"
         x-transition.opacity
         class="absolute inset-0 bg-slate-900/50"
         @click="$store.confirm.cancel()"></div>

    <div x-show="$store.confirm.open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-effect="if ($store.confirm.open) $nextTick(() => $refs.confirmBtn.focus())"
         class="relative w-full max-w-md bg-white rounded-2xl shadow-xl p-6">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0"
                 :class="$store.confirm.tone === 'success' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600'">
                <svg x-show="$store.confirm.tone !== 'success'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <svg x-show="$store.confirm.tone === 'success'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div class="flex-1">
                <h2 id="confirm-title" class="text-lg font-bold text-slate-900" x-text="$store.confirm.title"></h2>
                <p class="mt-1 text-sm text-slate-600" x-show="$store.confirm.body" x-text="$store.confirm.body"></p>
            </div>
        </div>
        <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
            <button type="button" @click="$store.confirm.cancel()"
                    class="px-4 py-2 rounded-xl text-sm font-semibold border border-slate-200 text-slate-700 hover:bg-slate-50 transition">
                Cancel
            </button>
            <button type="button" x-ref="confirmBtn" @click="$store.confirm.accept()"
                    class="px-4 py-2 rounded-xl text-sm font-bold text-white shadow-sm transition"
                    :class="$store.confirm.tone === 'success' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-rose-600 hover:bg-rose-700'"
                    x-text="$store.confirm.action"></button>
        </div>
    </div>
</div>
