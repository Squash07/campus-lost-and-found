<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <a href="{{ route('items.index') }}" class="p-2 rounded-xl border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Item Detail #{{ $item->id }}</span>
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $item->title }}</h1>
                </div>
            </div>

            <!-- Owner or Admin Actions (F4, F5, F9) -->
            <div class="flex flex-wrap items-center gap-2">
                @can('update', $item)
                    <a href="{{ route('items.edit', $item) }}" class="inline-flex items-center px-4 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-sm font-semibold shadow-sm transition">
                        <svg class="w-4 h-4 me-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Item
                    </a>
                @endcan

                @can('delete', $item)
                    <form method="POST" action="{{ route('items.destroy', $item) }}" onsubmit="return confirm('Are you sure you want to permanently delete this item report?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 rounded-xl border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 text-sm font-semibold transition">
                            <svg class="w-4 h-4 me-1.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                    </form>
                @endcan

                @if($isOwner && $item->status === 'claimed')
                    <form method="POST" action="{{ route('items.returned', $item) }}" onsubmit="return confirm('Confirm that this item has been returned to the rightful owner?');" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="inline-flex items-center px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold shadow-sm transition">
                            <svg class="w-4 h-4 me-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Mark as Returned
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <!-- Main Item Details Card (F3) -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-0">
                <!-- Left: Photo / Image -->
                <div class="lg:col-span-5 bg-slate-100 flex items-center justify-center min-h-[320px] p-4 border-b lg:border-b-0 lg:border-r border-slate-200">
                    @if($item->photo_path)
                        <img src="{{ asset('uploads/' . $item->photo_path) }}"
                             alt="{{ $item->title }}"
                             class="max-h-96 w-full object-contain rounded-xl shadow-sm">
                    @else
                        <div class="flex flex-col items-center justify-center text-slate-400 py-12 text-center">
                            <div class="w-20 h-20 rounded-2xl bg-slate-200 flex items-center justify-center mb-3">
                                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-slate-500">No Photo Uploaded</p>
                            <p class="text-xs text-slate-400">Poster did not attach an image</p>
                        </div>
                    @endif
                </div>

                <!-- Right: Information & Metadata -->
                <div class="lg:col-span-7 p-6 sm:p-8 flex flex-col justify-between">
                    <div>
                        <!-- Badges Row -->
                        <div class="flex flex-wrap items-center gap-2 mb-4">
                            @if($item->type === 'lost')
                                <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-rose-600 text-white shadow-sm">
                                    Lost Item Report
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-emerald-600 text-white shadow-sm">
                                    Found Item Report
                                </span>
                            @endif

                            <span class="px-3 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-800 border border-slate-200">
                                {{ $item->category->name }}
                            </span>

                            @if($item->status === 'open')
                                <span class="px-3 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                    Status: Open
                                </span>
                            @elseif($item->status === 'claimed')
                                <span class="px-3 py-1 rounded-lg text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                    Status: Claimed
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-lg text-xs font-bold bg-slate-200 text-slate-700 border border-slate-300">
                                    Status: Returned
                                </span>
                            @endif
                        </div>

                        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-4">{{ $item->title }}</h2>

                        <!-- Info Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 py-4 my-4 border-y border-slate-100 text-sm">
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-400 font-semibold">Campus Location</p>
                                    <p class="font-bold text-slate-800">{{ $item->location }}</p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-400 font-semibold">Date of Event</p>
                                    <p class="font-bold text-slate-800">{{ $item->event_date->format('l, d F Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Description & Notes</h3>
                            <p class="text-slate-700 text-sm sm:text-base leading-relaxed whitespace-pre-line">{{ $item->description }}</p>
                        </div>
                    </div>

                    <!-- Poster Footer info -->
                    <div class="pt-6 mt-6 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                        <div class="flex items-center space-x-2">
                            <div class="w-7 h-7 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold">
                                {{ strtoupper(substr($item->user->name, 0, 1)) }}
                            </div>
                            <span>Reported by <strong class="text-slate-800">{{ $item->user->name }}</strong></span>
                        </div>
                        <span>Posted {{ $item->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section: Claims & Actions -->
        @if($isOwner)
            <!-- Poster View: Manage Claims (F8) -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Claims on this Item ({{ $claims->count() }})</h2>
                        <p class="text-sm text-slate-500">Review claims submitted by claimants. Approving a claim will share contact info and automatically reject other pending claims.</p>
                    </div>
                    @if($item->status === 'claimed')
                        <span class="px-3 py-1 text-xs font-bold rounded-lg bg-amber-100 text-amber-800 border border-amber-300">
                            Claim Approved &bull; Ready to return
                        </span>
                    @endif
                </div>

                @if($claims->count() > 0)
                    <div class="space-y-4">
                        @foreach($claims as $claim)
                            <div class="p-5 rounded-xl border {{ $claim->status === 'approved' ? 'border-emerald-300 bg-emerald-50/40' : ($claim->status === 'rejected' ? 'border-slate-200 bg-slate-50/70 opacity-75' : 'border-slate-200 bg-white') }} transition">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-7 h-7 rounded-full bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-xs">
                                            {{ strtoupper(substr($claim->user->name, 0, 1)) }}
                                        </div>
                                        <span class="font-bold text-slate-900 text-sm">{{ $claim->user->name }}</span>
                                        <span class="text-xs text-slate-400">({{ $claim->user->email }})</span>
                                        <span class="text-xs text-slate-400">&bull; {{ $claim->created_at->diffForHumans() }}</span>
                                    </div>

                                    <div>
                                        @if($claim->status === 'pending')
                                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-amber-100 text-amber-800 border border-amber-200">
                                                Pending Review
                                            </span>
                                        @elseif($claim->status === 'approved')
                                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-100 text-emerald-800 border border-emerald-300">
                                                Approved
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-slate-200 text-slate-600">
                                                Rejected
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="text-sm text-slate-700 bg-white p-3.5 rounded-lg border border-slate-100 mb-3 whitespace-pre-line">
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Claimant's Proof Description:</p>
                                    {{ $claim->message }}
                                </div>

                                @if($claim->status === 'approved')
                                    <div class="p-3 bg-emerald-100 border border-emerald-300 rounded-lg text-emerald-900 text-sm flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-5 h-5 text-emerald-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                            <span><strong>Claimant Contact:</strong> <span class="font-mono text-base font-bold">{{ $claim->contact }}</span></span>
                                        </div>
                                        <span class="text-xs font-semibold text-emerald-700">Please reach out to coordinate handover!</span>
                                    </div>
                                @elseif($claim->status === 'pending')
                                    <div class="flex items-center justify-end space-x-2 pt-2">
                                        <form method="POST" action="{{ route('claims.reject', $claim) }}" onsubmit="return confirm('Reject this claim?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-3.5 py-1.5 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-100 text-xs font-bold transition">
                                                Reject Claim
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('claims.approve', $claim) }}" onsubmit="return confirm('Approve this claim? This will mark the item as Claimed and reject all other pending claims.');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="px-4 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition">
                                                Approve Claim
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-8 text-center text-slate-400 text-sm">
                        <svg class="w-12 h-12 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="font-medium text-slate-600">No claims submitted yet.</p>
                        <p class="text-xs text-slate-400">Claims made by other students will appear here for your review.</p>
                    </div>
                @endif
            </div>
        @else
            <!-- Non-Owner View: Submit Claim Form (F7) or Status -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
                @guest
                    <!-- Guest Prompt -->
                    <div class="text-center py-6">
                        <div class="w-12 h-12 rounded-2xl bg-sky-50 text-sky-600 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-1">Is this item yours, or did you find it?</h3>
                        <p class="text-sm text-slate-500 mb-4 max-w-md mx-auto">Please log in or create an account to submit an ownership proof claim to the poster.</p>
                        <div class="flex justify-center gap-3">
                            <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-sky-600 text-white hover:bg-sky-700 transition">
                                Log In to Claim
                            </a>
                            <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl text-sm font-semibold border border-slate-200 text-slate-700 hover:bg-slate-50 transition">
                                Register
                            </a>
                        </div>
                    </div>
                @else
                    @if($userClaim)
                        <!-- User Already Submitted A Claim -->
                        <div>
                            <div class="flex items-center space-x-3 mb-4">
                                <span class="w-3 h-3 rounded-full {{ $userClaim->status === 'approved' ? 'bg-emerald-500' : ($userClaim->status === 'rejected' ? 'bg-rose-500' : 'bg-amber-500') }}"></span>
                                <h3 class="text-lg font-extrabold text-slate-900">Your Submitted Claim</h3>
                                <span class="px-2.5 py-0.5 text-xs font-bold rounded-full {{ $userClaim->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($userClaim->status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                    {{ ucfirst($userClaim->status) }}
                                </span>
                            </div>

                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-sm space-y-2">
                                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Your Proof Message:</p>
                                <p class="text-slate-800 whitespace-pre-line">{{ $userClaim->message }}</p>
                                <p class="text-xs text-slate-500 pt-2 border-t border-slate-200">Contact given: <strong class="text-slate-700">{{ $userClaim->contact }}</strong> &bull; Submitted {{ $userClaim->created_at->diffForHumans() }}</p>
                            </div>

                            @if($userClaim->status === 'approved')
                                <div class="mt-4 p-4 rounded-xl bg-emerald-50 border border-emerald-300 text-emerald-900 text-sm">
                                    <p class="font-bold flex items-center">
                                        <svg class="w-5 h-5 text-emerald-600 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Congratulations! Your claim was approved by the poster.
                                    </p>
                                    <p class="mt-1 text-xs text-emerald-800">The poster has received your contact info and will reach out to return the item.</p>
                                </div>
                            @elseif($userClaim->status === 'rejected')
                                <div class="mt-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 text-sm">
                                    <p class="font-bold">Your claim was not accepted by the poster.</p>
                                    <p class="mt-1 text-xs text-rose-700">If you believe there was a mistake, you can message the administrator.</p>
                                </div>
                            @else
                                <p class="mt-4 text-xs text-slate-500">Your claim is currently awaiting review by {{ $item->user->name }}. You will see updates here and on your dashboard.</p>
                            @endif
                        </div>
                    @elseif($item->status !== 'open')
                        <!-- Item is already claimed or returned -->
                        <div class="text-center py-6">
                            <span class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </span>
                            <h3 class="text-lg font-bold text-slate-900 mb-1">Item No Longer Open</h3>
                            <p class="text-sm text-slate-500">This item is currently marked as <strong class="uppercase text-slate-700">{{ $item->status }}</strong> and is no longer accepting new claims.</p>
                        </div>
                    @else
                        <!-- Submit Claim Form (F7) -->
                        <div>
                            <div class="mb-4">
                                <h3 class="text-lg font-extrabold text-slate-900">Claim This Item</h3>
                                <p class="text-sm text-slate-500">Describe proof of ownership (serial number, hidden markings, contents, or circumstances) so the poster can verify you.</p>
                            </div>

                            <form method="POST" action="{{ route('claims.store', $item) }}" class="space-y-4">
                                @csrf

                                <div>
                                    <label for="message" class="block text-sm font-bold text-slate-800 mb-1">
                                        Proof of Ownership / Details <span class="text-rose-500">*</span>
                                    </label>
                                    <textarea id="message"
                                              name="message"
                                              rows="4"
                                              required
                                              minlength="20"
                                              placeholder="Provide detailed proof (minimum 20 characters), e.g., 'The wallet contains my KTM card with ID 5025211000, 2 keys, and a receipt from the cafeteria dated Tuesday...'"
                                              class="w-full px-4 py-2.5 rounded-xl border-slate-200 focus:border-sky-500 focus:ring-sky-500 text-sm">{{ old('message') }}</textarea>
                                    @error('message')
                                        <p class="mt-1.5 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="contact" class="block text-sm font-bold text-slate-800 mb-1">
                                        Contact Information <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text"
                                           id="contact"
                                           name="contact"
                                           value="{{ old('contact') }}"
                                           required
                                           maxlength="50"
                                           placeholder="e.g. WhatsApp: +62 812-3456-7890 or LINE: username"
                                           class="w-full px-4 py-2.5 rounded-xl border-slate-200 focus:border-sky-500 focus:ring-sky-500 text-sm">
                                    <p class="text-xs text-slate-400 mt-1">This will only be revealed to the poster once your claim is approved.</p>
                                    @error('contact')
                                        <p class="mt-1.5 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="pt-2">
                                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl shadow-md shadow-sky-600/20 transition">
                                        Submit Ownership Claim
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                @endguest
            </div>
        @endif
    </div>
</x-app-layout>
