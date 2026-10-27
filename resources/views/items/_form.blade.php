{{--
    Shared form for posting (create) and editing an item.
    Expects: $categories, optional $item (edit mode).
--}}
@php
    $item = $item ?? null;
    // Input classes, with a red border when that field failed validation
    $field = fn (string $name) => 'mt-1 w-full px-4 py-2.5 rounded-xl text-sm focus:border-sky-500 focus:ring-sky-500 ' .
        ($errors->has($name) ? 'border-rose-400 bg-rose-50/40' : 'border-slate-300');
    $initialType = old('type', $item?->type ?? (in_array(request('type'), ['lost', 'found']) ? request('type') : 'lost'));
@endphp

<div class="space-y-6" x-data="{ type: @js($initialType) }">
    <!-- Lost / Found -->
    <fieldset>
        <legend class="block text-sm font-semibold text-slate-800">What happened?</legend>
        <div class="mt-2 grid grid-cols-2 gap-3">
            <label class="flex items-center justify-center gap-2 p-4 rounded-xl border-2 cursor-pointer transition font-semibold text-sm has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-sky-500"
                   :class="type === 'lost' ? 'border-rose-500 bg-rose-50 text-rose-900' : 'border-slate-200 text-slate-600 hover:border-slate-300'">
                <input type="radio" name="type" value="lost" class="sr-only" x-model="type">
                I lost something
            </label>
            <label class="flex items-center justify-center gap-2 p-4 rounded-xl border-2 cursor-pointer transition font-semibold text-sm has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-sky-500"
                   :class="type === 'found' ? 'border-emerald-500 bg-emerald-50 text-emerald-900' : 'border-slate-200 text-slate-600 hover:border-slate-300'">
                <input type="radio" name="type" value="found" class="sr-only" x-model="type">
                I found something
            </label>
        </div>
        @error('type') <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
    </fieldset>

    <!-- Title -->
    <div>
        <label for="title" class="block text-sm font-semibold text-slate-800">Item name</label>
        <input type="text" id="title" name="title" value="{{ old('title', $item?->title) }}" maxlength="100" required
               placeholder="e.g. Black AirPods Pro case" class="{{ $field('title') }}">
        @error('title') <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Category -->
        <div>
            <label for="category_id" class="block text-sm font-semibold text-slate-800">Category</label>
            <select id="category_id" name="category_id" required class="{{ $field('category_id') }}">
                <option value="" disabled @selected(! old('category_id', $item?->category_id))>Choose a category</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(old('category_id', $item?->category_id) == $cat->id)>{{ $cat->name }}</option>
                @endforeach
            </select>
            @error('category_id') <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
        </div>

        <!-- Date -->
        <div>
            <label for="event_date" class="block text-sm font-semibold text-slate-800">
                <span x-text="type === 'found' ? 'Date found' : 'Date lost'">Date</span>
            </label>
            <input type="date" id="event_date" name="event_date" max="{{ now()->toDateString() }}" required
                   value="{{ old('event_date', $item?->event_date?->toDateString() ?? now()->toDateString()) }}"
                   class="{{ $field('event_date') }}">
            @error('event_date') <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
        </div>
    </div>

    <!-- Location -->
    <div>
        <label for="location" class="block text-sm font-semibold text-slate-800">
            <span x-text="type === 'found' ? 'Where did you find it?' : 'Where did you last have it?'">Location</span>
        </label>
        <input type="text" id="location" name="location" value="{{ old('location', $item?->location) }}" maxlength="150" required
               aria-describedby="location-help" placeholder="e.g. Central Library, 2nd floor reading room"
               class="{{ $field('location') }}">
        @error('location')
            <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
        @else
            <p id="location-help" class="mt-1 text-xs text-slate-500">Be specific: building, floor and a landmark help the most.</p>
        @enderror
    </div>

    <!-- Description -->
    <div>
        <label for="description" class="block text-sm font-semibold text-slate-800">Description</label>
        <textarea id="description" name="description" rows="4" required aria-describedby="description-help"
                  placeholder="Colour, brand, stickers, what was inside..."
                  class="{{ $field('description') }}">{{ old('description', $item?->description) }}</textarea>
        @error('description')
            <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
        @else
            <p id="description-help" class="mt-1 text-xs text-slate-500" x-show="type === 'found'">Tip: leave out one detail (like what's inside) so you can check that a claim is genuine.</p>
        @enderror
    </div>

    <!-- Photo -->
    <div x-data="{
            preview: null,
            name: '',
            tooBig: false,
            dragging: false,
            pick(files) {
                const file = files && files[0];
                if (!file) return;
                this.tooBig = file.size > 2 * 1024 * 1024;
                this.name = file.name;
                const reader = new FileReader();
                reader.onload = (e) => this.preview = e.target.result;
                reader.readAsDataURL(file);
            },
            clear() {
                this.preview = null; this.name = ''; this.tooBig = false;
                $refs.photo.value = '';
            },
        }">
        <span class="block text-sm font-semibold text-slate-800">Photo <span class="font-normal text-slate-500">(optional)</span></span>

        @if($item?->photo_path)
            <div class="mt-2 flex items-center gap-3 text-sm text-slate-600" x-show="!preview">
                <img src="{{ asset('uploads/' . $item->photo_path) }}" alt="Current photo" class="w-16 h-16 object-cover rounded-lg ring-1 ring-slate-200">
                <span>Current photo. Choose a new one to replace it.</span>
            </div>
        @endif

        <label for="photo"
               @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false"
               @drop.prevent="dragging = false; $refs.photo.files = $event.dataTransfer.files; pick($event.dataTransfer.files)"
               x-show="!preview"
               class="mt-2 flex flex-col items-center justify-center gap-2 px-6 py-8 rounded-xl border-2 border-dashed cursor-pointer transition text-center has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-sky-500"
               :class="dragging ? 'border-sky-500 bg-sky-50' : 'border-slate-300 hover:border-sky-400 bg-slate-50'">
            <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
            <span class="text-sm"><span class="font-semibold text-sky-700">Choose a photo</span> or drag it here</span>
            <span class="text-xs text-slate-500">JPG or PNG, up to 2 MB</span>
            <input id="photo" name="photo" type="file" accept="image/png,image/jpeg" class="sr-only" x-ref="photo" @change="pick($event.target.files)">
        </label>

        <div x-show="preview" x-cloak class="mt-2 flex items-center gap-4 p-3 rounded-xl ring-1 ring-slate-200">
            <img :src="preview" alt="Selected photo preview" class="w-24 h-24 object-cover rounded-lg">
            <div class="flex-1 min-w-0 text-sm">
                <p class="font-semibold text-slate-800 truncate" x-text="name"></p>
                <p x-show="tooBig" class="text-rose-600 font-semibold">This file is over 2 MB. Choose a smaller photo.</p>
                <button type="button" @click="clear()" class="mt-1 font-semibold text-slate-600 hover:text-rose-700">Remove</button>
            </div>
        </div>
        @error('photo') <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p> @enderror
    </div>
</div>
