<x-app-layout>
    <x-slot name="title">Post an item</x-slot>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Post an item</h1>
        <p class="mt-1 text-sm text-slate-500">Clear details and a photo help it get back to its owner faster.</p>

        <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data"
              class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
            @csrf
            @include('items._form')

            <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('items.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-900 rounded-xl hover:bg-slate-100 transition">Cancel</a>
                <button type="submit" data-loading="Posting..." class="px-6 py-2.5 text-sm font-bold text-white bg-sky-700 hover:bg-sky-800 rounded-xl shadow-sm transition">Post item</button>
            </div>
        </form>
    </div>
</x-app-layout>
