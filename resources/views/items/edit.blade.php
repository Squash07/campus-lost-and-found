<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Edit Item Report</h1>
                <p class="text-sm text-slate-500 mt-1">Update the details or photo of your posted item.</p>
            </div>
            <a href="{{ route('items.show', $item) }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 flex items-center">
                &larr; View Item
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
            <form method="POST" action="{{ route('items.update', $item) }}" enctype="multipart/form-data" class="space-y-6" x-data="{ photoPreview: null, type: '{{ old('type', $item->type) }}' }">
                @csrf
                @method('PUT')

                <!-- Type Selector (Lost / Found) -->
                <div>
                    <label class="block text-sm font-bold text-slate-800 mb-2">Report Type <span class="text-rose-500">*</span></label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative flex items-center justify-center p-4 border rounded-xl cursor-pointer transition select-none"
                               :class="type === 'lost' ? 'border-rose-500 bg-rose-50/50 ring-2 ring-rose-500 text-rose-900' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'">
                            <input type="radio" name="type" value="lost" class="sr-only" x-model="type">
                            <div class="flex items-center space-x-2 font-bold text-sm">
                                <span class="w-2.5 h-2.5 rounded-full" :class="type === 'lost' ? 'bg-rose-500' : 'bg-slate-300'"></span>
                                <span>I Lost Something</span>
                            </div>
                        </label>

                        <label class="relative flex items-center justify-center p-4 border rounded-xl cursor-pointer transition select-none"
                               :class="type === 'found' ? 'border-emerald-500 bg-emerald-50/50 ring-2 ring-emerald-500 text-emerald-900' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50'">
                            <input type="radio" name="type" value="found" class="sr-only" x-model="type">
                            <div class="flex items-center space-x-2 font-bold text-sm">
                                <span class="w-2.5 h-2.5 rounded-full" :class="type === 'found' ? 'bg-emerald-500' : 'bg-slate-300'"></span>
                                <span>I Found An Item</span>
                            </div>
                        </label>
                    </div>
                    @error('type')
                        <p class="mt-1.5 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Item Title -->
                <div>
                    <label for="title" class="block text-sm font-bold text-slate-800 mb-1">Item Title <span class="text-rose-500">*</span></label>
                    <input type="text"
                           id="title"
                           name="title"
                           value="{{ old('title', $item->title) }}"
                           maxlength="100"
                           required
                           class="w-full px-4 py-2.5 rounded-xl border-slate-200 focus:border-sky-500 focus:ring-sky-500 text-sm">
                    @error('title')
                        <p class="mt-1.5 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category & Event Date Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="category_id" class="block text-sm font-bold text-slate-800 mb-1">Category <span class="text-rose-500">*</span></label>
                        <select id="category_id"
                                name="category_id"
                                required
                                class="w-full px-4 py-2.5 rounded-xl border-slate-200 focus:border-sky-500 focus:ring-sky-500 text-sm">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $item->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1.5 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="event_date" class="block text-sm font-bold text-slate-800 mb-1">Date Lost / Found <span class="text-rose-500">*</span></label>
                        <input type="date"
                               id="event_date"
                               name="event_date"
                               max="{{ date('Y-m-d') }}"
                               value="{{ old('event_date', $item->event_date->format('Y-m-d')) }}"
                               required
                               class="w-full px-4 py-2.5 rounded-xl border-slate-200 focus:border-sky-500 focus:ring-sky-500 text-sm">
                        @error('event_date')
                            <p class="mt-1.5 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Campus Location -->
                <div>
                    <label for="location" class="block text-sm font-bold text-slate-800 mb-1">Campus Location <span class="text-rose-500">*</span></label>
                    <input type="text"
                           id="location"
                           name="location"
                           value="{{ old('location', $item->location) }}"
                           maxlength="150"
                           required
                           class="w-full px-4 py-2.5 rounded-xl border-slate-200 focus:border-sky-500 focus:ring-sky-500 text-sm">
                    @error('location')
                        <p class="mt-1.5 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Detailed Description -->
                <div>
                    <label for="description" class="block text-sm font-bold text-slate-800 mb-1">Detailed Description <span class="text-rose-500">*</span></label>
                    <textarea id="description"
                              name="description"
                              rows="4"
                              required
                              class="w-full px-4 py-2.5 rounded-xl border-slate-200 focus:border-sky-500 focus:ring-sky-500 text-sm">{{ old('description', $item->description) }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Photo Upload with Preview and Current Photo Display -->
                <div>
                    <label class="block text-sm font-bold text-slate-800 mb-1">Photo (Upload new to replace)</label>
                    <p class="text-xs text-slate-500 mb-2">Max 2 MB, JPG or PNG formats only.</p>

                    @if($item->photo_path)
                        <div class="mb-3 flex items-center gap-4 p-3 bg-slate-50 rounded-xl border border-slate-200">
                            <img src="{{ asset('uploads/' . $item->photo_path) }}" alt="{{ $item->title }}" class="w-16 h-16 object-cover rounded-lg border">
                            <div class="text-xs text-slate-600">
                                <p class="font-bold text-slate-800">Current Photo</p>
                                <p class="text-slate-400">Selecting a new image below will replace this file.</p>
                            </div>
                        </div>
                    @endif

                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:border-sky-400 transition bg-slate-50/50">
                        <div class="space-y-2 text-center" x-show="!photoPreview">
                            <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-slate-600 justify-center">
                                <label for="photo" class="relative cursor-pointer bg-white rounded-md font-bold text-sky-600 hover:text-sky-500 focus-within:outline-none px-2 py-0.5">
                                    <span>Select a new photo</span>
                                    <input id="photo"
                                           name="photo"
                                           type="file"
                                           accept="image/png, image/jpeg, image/jpg"
                                           class="sr-only"
                                           @change="
                                               const file = $event.target.files[0];
                                               if (file) {
                                                   const reader = new FileReader();
                                                   reader.onload = (e) => { photoPreview = e.target.result; };
                                                   reader.readAsDataURL(file);
                                               }
                                           ">
                                </label>
                            </div>
                        </div>

                        <!-- Preview Area -->
                        <div x-show="photoPreview" x-cloak class="relative">
                            <img :src="photoPreview" class="h-48 w-auto rounded-xl object-cover shadow-sm mx-auto">
                            <button type="button"
                                    @click="photoPreview = null; document.getElementById('photo').value = ''"
                                    class="absolute -top-2 -right-2 bg-rose-600 hover:bg-rose-700 text-white rounded-full p-1 shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @error('photo')
                        <p class="mt-1.5 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit / Cancel Actions -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                    <a href="{{ route('items.show', $item) }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 rounded-xl hover:bg-slate-100 transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-sky-600 hover:bg-sky-700 rounded-xl shadow-md shadow-sky-600/20 transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
