<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Hi, {{ Str::before(Auth::user()->name, ' ') }}</h1>
                <p class="mt-1 text-sm text-slate-500">Your posts, the responses to them, and the claims you've sent.</p>
            </div>
        </div>

        <!-- Stats (F11) -->
        <dl class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach([
                ['Needs your review', $stats['to_review'], $stats['to_review'] > 0 ? 'text-rose-700' : 'text-slate-900'],
                ['Open posts', $stats['open_items'], 'text-emerald-700'],
                ['Claimed, awaiting handover', $stats['claimed_items'], 'text-amber-700'],
                ['Returned', $stats['returned_items'], 'text-slate-900'],
            ] as [$label, $value, $color])
                <div class="bg-white p-5 rounded-2xl border border-slate-200">
                    <dt class="text-sm text-slate-500">{{ $label }}</dt>
                    <dd class="mt-1 text-3xl font-extrabold {{ $color }}">{{ $value }}</dd>
                </div>
            @endforeach
        </dl>

        <!-- Needs your review: pending claims on my open items -->
        <section aria-labelledby="review-heading" class="bg-white rounded-2xl border {{ $reviewQueue->isNotEmpty() ? 'border-rose-200 ring-4 ring-rose-50' : 'border-slate-200' }}">
            <div class="px-6 py-5 border-b border-slate-100">
                <h2 id="review-heading" class="text-lg font-bold text-slate-900">Needs your review</h2>
                <p class="text-sm text-slate-500">People responding to your posts. Approve the right one to see their contact.</p>
            </div>

            @if($reviewQueue->isNotEmpty())
                <ul class="divide-y divide-slate-100">
                    @foreach($reviewQueue as $claim)
                        <li class="p-6 flex flex-col lg:flex-row gap-4 lg:items-start">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm">
                                    <span class="font-semibold text-slate-900">{{ $claim->user->name }}</span>
                                    <span class="text-slate-500">{{ $claim->item->type === 'found' ? 'says this is theirs:' : 'says they found:' }}</span>
                                    <a href="{{ route('items.show', $claim->item) }}" class="font-semibold text-sky-700 hover:underline">{{ $claim->item->title }}</a>
                                </p>
                                <p class="mt-2 text-sm text-slate-700 line-clamp-3">{{ $claim->message }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $claim->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex gap-2 shrink-0">
                                <form method="POST" action="{{ route('claims.reject', $claim) }}"
                                      data-confirm="Decline {{ $claim->user->name }}'s response?" data-confirm-action="Decline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-100 text-sm font-semibold transition">Decline</button>
                                </form>
                                <form method="POST" action="{{ route('claims.approve', $claim) }}"
                                      data-confirm="Approve {{ $claim->user->name }}?"
                                      data-confirm-body="You'll see their contact to arrange the handover. Other pending responses on this item will be declined."
                                      data-confirm-action="Approve" data-confirm-tone="success">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold transition">Approve</button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="px-6 py-8 text-sm text-slate-500">Nothing to review right now. New responses to your posts will appear here.</p>
            @endif
        </section>

        <!-- My posts / My claims -->
        <section x-data="{ tab: 'items' }" class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="px-6 pt-4 border-b border-slate-200 flex gap-6" role="tablist">
                <button type="button" role="tab" :aria-selected="(tab === 'items').toString()" @click="tab = 'items'"
                        class="pb-3 -mb-px border-b-2 text-sm font-semibold transition"
                        :class="tab === 'items' ? 'border-sky-700 text-sky-800' : 'border-transparent text-slate-500 hover:text-slate-800'">
                    My posts <span class="text-slate-400">({{ $myItems->count() }})</span>
                </button>
                <button type="button" role="tab" :aria-selected="(tab === 'claims').toString()" @click="tab = 'claims'"
                        class="pb-3 -mb-px border-b-2 text-sm font-semibold transition"
                        :class="tab === 'claims' ? 'border-sky-700 text-sky-800' : 'border-transparent text-slate-500 hover:text-slate-800'">
                    Claims I sent <span class="text-slate-400">({{ $myClaims->count() }})</span>
                </button>
            </div>

            <!-- My posts -->
            <div x-show="tab === 'items'" role="tabpanel">
                @if($myItems->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-slate-500">
                                <tr>
                                    <th scope="col" class="py-3 px-6 font-semibold">Item</th>
                                    <th scope="col" class="py-3 px-6 font-semibold">Status</th>
                                    <th scope="col" class="py-3 px-6 font-semibold">Responses</th>
                                    <th scope="col" class="py-3 px-6 font-semibold">Posted</th>
                                    <th scope="col" class="py-3 px-6"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($myItems as $item)
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-3">
                                                <div class="w-12 h-12 rounded-lg overflow-hidden shrink-0"><x-item-photo :item="$item" /></div>
                                                <div class="min-w-0">
                                                    <a href="{{ route('items.show', $item) }}" class="font-semibold text-slate-900 hover:text-sky-700 line-clamp-1">{{ $item->title }}</a>
                                                    <div class="mt-0.5 flex items-center gap-2">
                                                        <x-type-badge :type="$item->type" />
                                                        <span class="text-xs text-slate-500">{{ $item->category->name }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6"><x-status-badge :status="$item->status" /></td>
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            {{ $item->claims_count }}
                                            @if($item->pending_claims_count > 0 && $item->status === 'open')
                                                <span class="ms-1 text-xs font-semibold text-rose-700">{{ $item->pending_claims_count }} new</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-slate-500 whitespace-nowrap">{{ $item->created_at->diffForHumans() }}</td>
                                        <td class="py-4 px-6 text-right whitespace-nowrap">
                                            <a href="{{ route('items.edit', $item) }}" class="font-semibold text-sky-700 hover:text-sky-900">Edit</a>
                                            <form method="POST" action="{{ route('items.destroy', $item) }}" class="inline ms-3"
                                                  data-confirm="Delete &quot;{{ $item->title }}&quot;?"
                                                  data-confirm-body="The post, its photo and all responses will be removed. This can't be undone."
                                                  data-confirm-action="Delete post">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="font-semibold text-rose-600 hover:text-rose-800">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-10 text-center">
                        <p class="text-sm text-slate-500">You haven't posted anything yet.</p>
                        <a href="{{ route('items.create') }}" class="mt-3 inline-block text-sm font-semibold text-sky-700 hover:underline">Post a lost or found item</a>
                    </div>
                @endif
            </div>

            <!-- Claims I sent -->
            <div x-show="tab === 'claims'" x-cloak role="tabpanel">
                @if($myClaims->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-slate-500">
                                <tr>
                                    <th scope="col" class="py-3 px-6 font-semibold">Item</th>
                                    <th scope="col" class="py-3 px-6 font-semibold">Posted by</th>
                                    <th scope="col" class="py-3 px-6 font-semibold">Status</th>
                                    <th scope="col" class="py-3 px-6 font-semibold">Sent</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($myClaims as $claim)
                                    <tr class="hover:bg-slate-50">
                                        <td class="py-4 px-6">
                                            <a href="{{ route('items.show', $claim->item) }}" class="font-semibold text-slate-900 hover:text-sky-700">{{ $claim->item->title }}</a>
                                            <p class="text-xs text-slate-500 line-clamp-1 max-w-sm">{{ $claim->message }}</p>
                                        </td>
                                        <td class="py-4 px-6 text-slate-600 whitespace-nowrap">{{ $claim->item->user->name }}</td>
                                        <td class="py-4 px-6"><x-status-badge :status="$claim->status" /></td>
                                        <td class="py-4 px-6 text-slate-500 whitespace-nowrap">{{ $claim->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-10 text-center">
                        <p class="text-sm text-slate-500">You haven't claimed or responded to any item yet.</p>
                        <a href="{{ route('items.index') }}" class="mt-3 inline-block text-sm font-semibold text-sky-700 hover:underline">Browse items</a>
                    </div>
                @endif
            </div>
        </section>
    </div>
</x-app-layout>
