<x-app-layout>
    <x-slot name="title">Manage categories</x-slot>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center space-x-2">
                    <span class="px-2 py-0.5 text-xs font-bold bg-indigo-100 text-indigo-700 rounded">Admin Panel</span>
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Categories</h1>
                </div>
                <p class="text-sm text-slate-500 mt-1">Manage campus item categories. Categories with existing items cannot be deleted.</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-xl shadow-sm transition">
                + Add New Category
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-800">All Categories ({{ $categories->total() }})</h2>
                <span class="text-xs text-slate-500">Categories that still have items can't be deleted</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                            <th class="py-3 px-6">ID</th>
                            <th class="py-3 px-6">Category Name</th>
                            <th class="py-3 px-6">Slug</th>
                            <th class="py-3 px-6">Linked Items</th>
                            <th class="py-3 px-6">Created</th>
                            <th class="py-3 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($categories as $category)
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-4 px-6 text-slate-400 font-mono text-xs">#{{ $category->id }}</td>
                                <td class="py-4 px-6 font-bold text-slate-900">{{ $category->name }}</td>
                                <td class="py-4 px-6 text-slate-500 font-mono text-xs">{{ $category->slug }}</td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $category->items_count > 0 ? 'bg-sky-50 text-sky-700' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $category->items_count }} item(s)
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-xs text-slate-400">{{ $category->created_at->format('M d, Y') }}</td>
                                <td class="py-4 px-6 text-right space-x-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">
                                        Edit
                                    </a>

                                    @if($category->items_count === 0)
                                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="inline"
                                              data-confirm="Delete the {{ $category->name }} category?" data-confirm-action="Delete category">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-800">
                                                Delete
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-300 cursor-not-allowed" title="Move or delete its items first">
                                            Delete
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
