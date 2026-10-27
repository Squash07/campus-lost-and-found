<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">My Dashboard</h1>
                <p class="text-sm text-slate-500 mt-1">Manage your posted campus items and track the status of your claims.</p>
            </div>
            <a href="{{ route('items.create') }}" class="inline-flex items-center px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl shadow-sm transition">
                + Post New Item
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <!-- 4 Count Stats Cards (F11) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <!-- Total Posts -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center font-bold text-lg shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">My Posts</p>
                    <p class="text-2xl font-extrabold text-slate-900">{{ $stats['total_items'] }}</p>
                </div>
            </div>

            <!-- Open Items -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Open</p>
                    <p class="text-2xl font-extrabold text-emerald-900">{{ $stats['open_items'] }}</p>
                </div>
            </div>

            <!-- Claimed Items -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-amber-700 uppercase tracking-wider">Claimed</p>
                    <p class="text-2xl font-extrabold text-amber-900">{{ $stats['claimed_items'] }}</p>
                </div>
            </div>

            <!-- Pending Claims -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center space-x-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-indigo-700 uppercase tracking-wider">Pending Claims</p>
                    <p class="text-2xl font-extrabold text-indigo-900">{{ $stats['pending_claims'] }}</p>
                </div>
            </div>
        </div>

        <!-- Section 1: My Items Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-extrabold text-slate-900">My Posted Items</h2>
                    <p class="text-xs text-slate-500">Items you have reported on the campus feed</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                    {{ $myItems->count() }} items
                </span>
            </div>

            @if($myItems->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                                <th class="py-3 px-6">Item</th>
                                <th class="py-3 px-6">Type</th>
                                <th class="py-3 px-6">Category</th>
                                <th class="py-3 px-6">Status</th>
                                <th class="py-3 px-6">Claims</th>
                                <th class="py-3 px-6">Event Date</th>
                                <th class="py-3 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($myItems as $item)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="py-4 px-6 font-semibold text-slate-900">
                                        <a href="{{ route('items.show', $item) }}" class="hover:text-sky-600 transition flex items-center space-x-3">
                                            @if($item->photo_path)
                                                <img src="{{ asset('uploads/' . $item->photo_path) }}" class="w-10 h-10 object-cover rounded-lg shrink-0 border">
                                            @else
                                                <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center shrink-0 text-xs font-bold">
                                                    {{ strtoupper(substr($item->category->name, 0, 2)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <p class="line-clamp-1 font-bold">{{ $item->title }}</p>
                                                <p class="text-xs text-slate-400">{{ $item->location }}</p>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="py-4 px-6">
                                        @if($item->type === 'lost')
                                            <span class="px-2 py-0.5 rounded text-xs font-bold bg-rose-100 text-rose-700">Lost</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-xs font-bold bg-emerald-100 text-emerald-700">Found</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-slate-600">{{ $item->category->name }}</td>
                                    <td class="py-4 px-6">
                                        @if($item->status === 'open')
                                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">Open</span>
                                        @elseif($item->status === 'claimed')
                                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-amber-50 text-amber-700 border border-amber-200">Claimed</span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-slate-100 text-slate-600 border border-slate-200">Returned</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        <a href="{{ route('items.show', $item) }}" class="text-xs font-bold text-sky-600 hover:text-sky-800">
                                            {{ $item->claims->count() }} claim(s)
                                        </a>
                                    </td>
                                    <td class="py-4 px-6 text-xs text-slate-500">{{ $item->event_date->format('M d, Y') }}</td>
                                    <td class="py-4 px-6 text-right space-x-2">
                                        <a href="{{ route('items.show', $item) }}" class="text-xs font-bold text-slate-600 hover:text-slate-900">View</a>
                                        <a href="{{ route('items.edit', $item) }}" class="text-xs font-bold text-sky-600 hover:text-sky-800">Edit</a>
                                        <form method="POST" action="{{ route('items.destroy', $item) }}" onsubmit="return confirm('Delete this item?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-800">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-8 text-center text-slate-400 text-sm">
                    <p>You haven't posted any items yet.</p>
                    <a href="{{ route('items.create') }}" class="mt-2 inline-block text-xs font-bold text-sky-600 hover:underline">
                        + Post your first item report
                    </a>
                </div>
            @endif
        </div>

        <!-- Section 2: My Claims Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-extrabold text-slate-900">My Submitted Claims</h2>
                    <p class="text-xs text-slate-500">Track ownership claims you made on items posted by others</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                    {{ $myClaims->count() }} claims
                </span>
            </div>

            @if($myClaims->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-slate-50 text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                                <th class="py-3 px-6">Claimed Item</th>
                                <th class="py-3 px-6">Poster</th>
                                <th class="py-3 px-6">Your Proof Description</th>
                                <th class="py-3 px-6">Status</th>
                                <th class="py-3 px-6">Claim Date</th>
                                <th class="py-3 px-6 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($myClaims as $claim)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="py-4 px-6 font-semibold text-slate-900">
                                        <a href="{{ route('items.show', $claim->item) }}" class="hover:text-sky-600 font-bold">
                                            {{ $claim->item->title }}
                                        </a>
                                        <p class="text-xs text-slate-400">{{ $claim->item->category->name }} &bull; {{ $claim->item->location }}</p>
                                    </td>
                                    <td class="py-4 px-6 text-slate-600 text-xs">
                                        {{ $claim->item->user->name }}
                                    </td>
                                    <td class="py-4 px-6 text-slate-600 text-xs max-w-xs truncate">
                                        {{ $claim->message }}
                                    </td>
                                    <td class="py-4 px-6">
                                        @if($claim->status === 'pending')
                                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-amber-50 text-amber-700 border border-amber-200">
                                                Pending Review
                                            </span>
                                        @elseif($claim->status === 'approved')
                                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Approved
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-rose-50 text-rose-700 border border-rose-200">
                                                Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-xs text-slate-500">{{ $claim->created_at->format('M d, Y') }}</td>
                                    <td class="py-4 px-6 text-right">
                                        <a href="{{ route('items.show', $claim->item) }}" class="text-xs font-bold text-sky-600 hover:text-sky-800">
                                            View Item &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-8 text-center text-slate-400 text-sm">
                    <p>You haven't submitted any ownership claims yet.</p>
                    <a href="{{ route('items.index') }}" class="mt-2 inline-block text-xs font-bold text-sky-600 hover:underline">
                        Browse the feed to find your missing items
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
