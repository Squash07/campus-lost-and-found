<x-app-layout>
    <x-slot name="title">New category</x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-indigo-600">Admin Action</span>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Create New Category</h1>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 flex items-center">
                &larr; Back to Categories
            </a>
        </div>
    </x-slot>

    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
            <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-bold text-slate-800 mb-1">
                        Category Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           maxlength="50"
                           placeholder="e.g. Laboratory Equipment"
                           class="w-full px-4 py-2.5 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <p class="text-xs text-slate-400 mt-1">Slug will be generated automatically from the name.</p>
                    @error('name')
                        <p class="mt-1.5 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 rounded-xl hover:bg-slate-100 transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-md shadow-indigo-600/20 transition">
                        Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
