<x-app-layout>
    <div class="min-h-[60vh] flex flex-col items-center justify-center px-4 py-16 text-center">
        <div class="w-20 h-20 rounded-3xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center mx-auto mb-6 shadow-sm">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-rose-100 text-rose-800 border border-rose-200 mb-3">
            HTTP 403 Forbidden
        </span>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-2">Access Denied / Unauthorized Action</h1>
        <p class="text-sm text-slate-500 max-w-md mx-auto mb-6 leading-relaxed">
            {{ $exception->getMessage() ?: 'You do not have permission to view or modify this resource. Policy authorization prevented this action.' }}
        </p>
        <div class="flex items-center justify-center gap-3">
            <a href="{{ url()->previous() }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold border border-slate-200 text-slate-700 hover:bg-slate-50 transition">
                &larr; Go Back
            </a>
            <a href="{{ route('items.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-sky-600 text-white hover:bg-sky-700 transition">
                Return to Feed
            </a>
        </div>
    </div>
</x-app-layout>
