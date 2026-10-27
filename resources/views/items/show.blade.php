<x-app-layout>
    <x-slot name="title">{{ $item->title }}</x-slot>

    @php
        // Wording depends on the report type: a FOUND item is claimed by its owner,
        // a LOST item is answered by someone who found it.
        $isFoundReport = $item->type === 'found';
        $claimNoun = $isFoundReport ? 'claim' : 'response';
        $steps = [
            ['label' => 'Posted', 'done' => true, 'date' => $item->created_at],
            ['label' => $isFoundReport ? 'Owner confirmed' : 'Finder confirmed', 'done' => in_array($item->status, ['claimed', 'returned']), 'date' => $approvedClaim?->updated_at],
            ['label' => 'Returned', 'done' => $item->status === 'returned', 'date' => $item->status === 'returned' ? $item->updated_at : null],
        ];
    @endphp

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('items.index') }}"
           class="inline-flex items-center gap-1 text-sm font-semibold text-slate-600 hover:text-slate-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back
        </a>

        <!-- Item details (F3) -->
        <article class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-12">
                <div class="lg:col-span-5 bg-slate-100 aspect-[4/3] lg:aspect-auto lg:min-h-[420px]">
                    <x-item-photo :item="$item" />
                </div>

                <div class="lg:col-span-7 p-6 sm:p-8 flex flex-col">
                    <div class="flex flex-wrap items-center gap-2">
                        <x-type-badge :type="$item->type" size="lg" />
                        <x-status-badge :status="$item->status" />
                        <span class="text-sm text-slate-500">{{ $item->category->name }}</span>
                    </div>

                    <h1 class="mt-4 text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $item->title }}</h1>

                    <dl class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-slate-500">{{ $isFoundReport ? 'Found at' : 'Last seen at' }}</dt>
                            <dd class="mt-0.5 font-semibold text-slate-900">{{ $item->location }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">{{ $isFoundReport ? 'Date found' : 'Date lost' }}</dt>
                            <dd class="mt-0.5 font-semibold text-slate-900">{{ $item->event_date->format('l, j F Y') }}</dd>
                        </div>
                    </dl>

                    <div class="mt-6">
                        <h2 class="text-sm text-slate-500">Description</h2>
                        <p class="mt-1 text-slate-800 leading-relaxed whitespace-pre-line">{{ $item->description }}</p>
                    </div>

                    <!-- Status timeline -->
                    <ol class="mt-8 mb-8 grid grid-cols-3 gap-2" aria-label="Item progress">
                        @foreach($steps as $i => $step)
                            <li class="relative">
                                <div class="h-1.5 rounded-full {{ $step['done'] ? ($item->status === 'returned' ? 'bg-slate-400' : 'bg-sky-600') : 'bg-slate-200' }}"></div>
                                <p class="mt-2 text-xs font-semibold {{ $step['done'] ? 'text-slate-900' : 'text-slate-400' }}">
                                    {{ $i + 1 }}. {{ $step['label'] }}
                                    <span class="sr-only">{{ $step['done'] ? '(done)' : '(not yet)' }}</span>
                                </p>
                                @if($step['done'] && $step['date'])
                                    <p class="text-xs text-slate-500">{{ $step['date']->format('j M Y') }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ol>

                    <div class="mt-auto pt-6 flex flex-wrap items-center justify-between gap-4 border-t border-slate-100">
                        <p class="text-sm text-slate-500">
                            Posted by <span class="font-semibold text-slate-800">{{ $item->user->name }}</span>
                            <time datetime="{{ $item->created_at->toIso8601String() }}" title="{{ $item->created_at->format('j F Y, H:i') }}">{{ $item->created_at->diffForHumans() }}</time>
                        </p>

                        <!-- Owner / admin actions (F4, F5, F9) -->
                        <div class="flex flex-wrap gap-2">
                            @if($isOwner && $item->status === 'claimed')
                                <form method="POST" action="{{ route('items.returned', $item) }}"
                                      data-confirm="Mark this item as returned?"
                                      data-confirm-body="Do this after the item has been handed over. The post will close and stop accepting {{ $claimNoun }}s."
                                      data-confirm-action="Mark as returned" data-confirm-tone="success">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold shadow-sm transition">Mark as returned</button>
                                </form>
                            @endif
                            @can('update', $item)
                                <a href="{{ route('items.edit', $item) }}" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50 text-sm font-semibold transition">Edit</a>
                            @endcan
                            @can('delete', $item)
                                <form method="POST" action="{{ route('items.destroy', $item) }}"
                                      data-confirm="Delete this post?"
                                      data-confirm-body="The post, its photo and all {{ $claimNoun }}s on it will be removed. This can't be undone."
                                      data-confirm-action="Delete post">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-4 py-2 rounded-xl border border-rose-200 text-rose-700 hover:bg-rose-50 text-sm font-semibold transition">
                                        {{ $isOwner ? 'Delete' : 'Delete (admin)' }}
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </article>

        @if($isOwner)
            <!-- Poster view: review claims (F8) -->
            <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8" aria-labelledby="claims-heading">
                <h2 id="claims-heading" class="text-xl font-extrabold text-slate-900">
                    {{ ucfirst($claimNoun) }}s <span class="text-slate-400 font-semibold">({{ $claims->count() }})</span>
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    @if($item->status === 'open')
                        {{ $isFoundReport
                            ? 'Check each proof against what you found. Approving one shares their contact with you and declines the others.'
                            : 'People who think they found your item. Approving one shares their contact with you and declines the others.' }}
                    @elseif($item->status === 'claimed')
                        Contact the approved person below to arrange the handover, then mark the item as returned.
                    @else
                        This item has been returned.
                    @endif
                </p>

                @if($claims->count() > 0)
                    <ul class="mt-6 space-y-4">
                        @foreach($claims as $claim)
                            <li class="p-5 rounded-xl border {{ $claim->status === 'approved' ? 'border-emerald-300 bg-emerald-50/50' : ($claim->status === 'rejected' ? 'border-slate-200 bg-slate-50 opacity-70' : 'border-slate-200') }}">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <p class="text-sm">
                                        <span class="font-bold text-slate-900">{{ $claim->user->name }}</span>
                                        <span class="text-slate-500">{{ $claim->created_at->diffForHumans() }}</span>
                                    </p>
                                    <x-status-badge :status="$claim->status" />
                                </div>
                                <p class="mt-3 text-sm text-slate-700 whitespace-pre-line">{{ $claim->message }}</p>

                                @if($claim->status === 'approved')
                                    <p class="mt-4 p-3 rounded-lg bg-white ring-1 ring-emerald-200 text-sm text-emerald-900">
                                        Contact: <span class="font-bold select-all">{{ $claim->contact }}</span>
                                    </p>
                                @elseif($claim->status === 'pending' && $item->status === 'open')
                                    <div class="mt-4 flex flex-wrap justify-end gap-2">
                                        <form method="POST" action="{{ route('claims.reject', $claim) }}"
                                              data-confirm="Decline {{ $claim->user->name }}'s {{ $claimNoun }}?"
                                              data-confirm-action="Decline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="px-4 py-2 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-100 text-sm font-semibold transition">Decline</button>
                                        </form>
                                        <form method="POST" action="{{ route('claims.approve', $claim) }}"
                                              data-confirm="Approve {{ $claim->user->name }}'s {{ $claimNoun }}?"
                                              data-confirm-body="You'll see their contact so you can arrange the handover. Other pending {{ $claimNoun }}s will be declined."
                                              data-confirm-action="Approve" data-confirm-tone="success">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold shadow-sm transition">Approve</button>
                                        </form>
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="mt-6 py-8 text-center text-sm text-slate-500 border border-dashed border-slate-200 rounded-xl">
                        No {{ $claimNoun }}s yet. They'll show up here, and on your dashboard, as soon as someone responds.
                    </p>
                @endif
            </section>
        @else
            <!-- Visitor view: claim form (F7) or claim status -->
            <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
                @guest
                    <div class="text-center py-4">
                        <h2 class="text-lg font-bold text-slate-900">{{ $isFoundReport ? 'Is this yours?' : 'Have you found this?' }}</h2>
                        <p class="mt-1 text-sm text-slate-500">Log in to contact {{ $item->user->name }} about this item.</p>
                        <div class="mt-5 flex justify-center gap-3">
                            <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-sky-700 text-white hover:bg-sky-800 transition">Log in</a>
                            <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold border border-slate-200 text-slate-700 hover:bg-slate-50 transition">Create account</a>
                        </div>
                    </div>
                @else
                    @if($userClaim)
                        <div class="flex flex-wrap items-center gap-3">
                            <h2 class="text-lg font-bold text-slate-900">Your {{ $claimNoun }}</h2>
                            <x-status-badge :status="$userClaim->status" />
                        </div>
                        <p class="mt-3 p-4 rounded-xl bg-slate-50 text-sm text-slate-800 whitespace-pre-line">{{ $userClaim->message }}</p>
                        <p class="mt-2 text-xs text-slate-500">Sent {{ $userClaim->created_at->diffForHumans() }} with contact {{ $userClaim->contact }}</p>
                        <p class="mt-4 text-sm {{ $userClaim->status === 'approved' ? 'text-emerald-800' : ($userClaim->status === 'rejected' ? 'text-rose-700' : 'text-slate-600') }}">
                            @if($userClaim->status === 'approved')
                                Approved. {{ $item->user->name }} can now see your contact and will reach out to arrange the handover.
                            @elseif($userClaim->status === 'rejected')
                                {{ $item->user->name }} declined this {{ $claimNoun }}. If you think that's a mistake, contact the campus admin.
                            @else
                                Waiting for {{ $item->user->name }} to review it. You can follow it from your dashboard.
                            @endif
                        </p>
                    @elseif($item->status !== 'open')
                        <div class="text-center py-4">
                            <h2 class="text-lg font-bold text-slate-900">This post is closed</h2>
                            <p class="mt-1 text-sm text-slate-500">
                                {{ $item->status === 'returned' ? 'The item has already been returned.' : 'The poster has already approved a ' . $claimNoun . '.' }}
                            </p>
                        </div>
                    @else
                        <h2 class="text-lg font-bold text-slate-900">{{ $isFoundReport ? 'This is mine' : 'I found this' }}</h2>
                        <p class="mt-1 text-sm text-slate-500">
                            {{ $isFoundReport
                                ? 'Describe something only the owner would know, like contents, a serial number or a hidden mark. Don\'t repeat what\'s already in the post.'
                                : 'Tell ' . $item->user->name . ' where and when you found it, and where it is now.' }}
                        </p>

                        <form method="POST" action="{{ route('claims.store', $item) }}" class="mt-6 space-y-5"
                              x-data="{ message: @js(old('message', '')) }">
                            @csrf
                            <div>
                                <label for="message" class="block text-sm font-semibold text-slate-800">{{ $isFoundReport ? 'Proof of ownership' : 'Where you found it' }}</label>
                                <textarea id="message" name="message" rows="4" required minlength="20" maxlength="1000"
                                          x-model="message" aria-describedby="message-help"
                                          placeholder="{{ $isFoundReport ? 'e.g. There is a KTM inside with my name, plus a BNI card and a receipt from the canteen.' : 'e.g. I found it on a bench outside Building C at around 2 pm. It is at the security post now.' }}"
                                          class="mt-1 w-full px-4 py-2.5 rounded-xl focus:border-sky-500 focus:ring-sky-500 text-sm {{ $errors->has('message') ? 'border-rose-400' : 'border-slate-300' }}"></textarea>
                                <div id="message-help" class="mt-1 flex justify-between text-xs">
                                    @error('message')
                                        <span class="text-rose-600 font-semibold">{{ $message }}</span>
                                    @else
                                        <span class="text-slate-500" x-text="message.length < 20 ? (20 - message.length) + ' more characters needed' : 'Looks good'">At least 20 characters</span>
                                    @enderror
                                    <span class="text-slate-400" x-text="message.length + ' / 1000'"></span>
                                </div>
                            </div>
                            <div>
                                <label for="contact" class="block text-sm font-semibold text-slate-800">How can they reach you?</label>
                                <input type="text" id="contact" name="contact" value="{{ old('contact') }}" required maxlength="50"
                                       aria-describedby="contact-help" placeholder="e.g. WhatsApp 0812-3456-7890 or LINE @username"
                                       class="mt-1 w-full px-4 py-2.5 rounded-xl focus:border-sky-500 focus:ring-sky-500 text-sm {{ $errors->has('contact') ? 'border-rose-400' : 'border-slate-300' }}">
                                @error('contact')
                                    <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                                @else
                                    <p id="contact-help" class="mt-1 text-xs text-slate-500">Only shown to {{ $item->user->name }} if they approve your {{ $claimNoun }}.</p>
                                @enderror
                            </div>
                            <button type="submit" data-loading="Sending..." class="w-full sm:w-auto px-6 py-2.5 bg-sky-700 hover:bg-sky-800 text-white font-bold text-sm rounded-xl shadow-sm transition">
                                Send {{ $claimNoun }}
                            </button>
                        </form>
                    @endif
                @endguest
            </section>
        @endif
    </div>
</x-app-layout>
