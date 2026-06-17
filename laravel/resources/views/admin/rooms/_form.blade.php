{{--
    Shared room form fields (used by both create and edit views).
    Expects an optional $room variable for edit mode.
--}}
@php $isEdit = isset($room); @endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

    {{-- Room Name --}}
    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-slate-300 mb-2">Room Name <span class="text-red-400">*</span></label>
        <input type="text" name="name" value="{{ old('name', $room->name ?? '') }}" required
               class="w-full bg-dark/60 border border-purple-900/50 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-primary-light"
               placeholder="e.g. The Purple Stage" />
        @error('name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Type --}}
    <div>
        <label class="block text-sm font-medium text-slate-300 mb-2">Room Type <span class="text-red-400">*</span></label>
        <select name="type" required
                class="w-full bg-dark/60 border border-purple-900/50 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-primary-light">
            @foreach(['standard' => 'Standard', 'deluxe' => 'Deluxe', 'vip' => 'VIP', 'party' => 'Party'] as $val => $label)
            <option value="{{ $val }}" @selected(old('type', $room->type ?? '') === $val)>{{ $label }}</option>
            @endforeach
        </select>
        @error('type')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Size --}}
    <div>
        <label class="block text-sm font-medium text-slate-300 mb-2">Room Size <span class="text-red-400">*</span></label>
        <select name="size" required
                class="w-full bg-dark/60 border border-purple-900/50 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-primary-light">
            @foreach(['small' => 'Small (2–6 pax)', 'medium' => 'Medium (7–12 pax)', 'large' => 'Large (13–20 pax)', 'xlarge' => 'XL Party (21–30+ pax)'] as $val => $label)
            <option value="{{ $val }}" @selected(old('size', $room->size ?? '') === $val)>{{ $label }}</option>
            @endforeach
        </select>
        @error('size')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Capacity --}}
    <div>
        <label class="block text-sm font-medium text-slate-300 mb-2">Max Capacity (pax) <span class="text-red-400">*</span></label>
        <input type="number" name="capacity" value="{{ old('capacity', $room->capacity ?? '') }}" min="1" required
               class="w-full bg-dark/60 border border-purple-900/50 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-primary-light" />
        @error('capacity')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Price --}}
    <div>
        <label class="block text-sm font-medium text-slate-300 mb-2">Price Per Hour (₱) <span class="text-red-400">*</span></label>
        <input type="number" step="0.01" name="price_per_hour" value="{{ old('price_per_hour', $room->price_per_hour ?? '') }}" min="0" required
               class="w-full bg-dark/60 border border-purple-900/50 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-primary-light" />
        @error('price_per_hour')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Description --}}
    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-slate-300 mb-2">Description</label>
        <textarea name="description" rows="3"
                  class="w-full bg-dark/60 border border-purple-900/50 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-primary-light resize-none placeholder-slate-600"
                  placeholder="Describe the room features and atmosphere...">{{ old('description', $room->description ?? '') }}</textarea>
    </div>

    {{-- Amenities --}}
    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-slate-300 mb-2">Amenities</label>
        <p class="text-xs text-slate-500 mb-3">Check all that apply</p>
        @php
        $allAmenities   = ['Microphones','HD TV Screen','Surround Sound','Air Conditioning','Sofa Seating','Private Bathroom','Mini Bar','Party Lights','Tambourine','Song Book','WiFi','Disco Ball'];
        $selectedItems  = old('amenities', $room->amenities ?? []);
        @endphp
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
            @foreach($allAmenities as $amenity)
            <label class="flex items-center gap-2 text-sm text-slate-300 cursor-pointer hover:text-white">
                <input type="checkbox" name="amenities[]" value="{{ $amenity }}"
                       class="rounded border-purple-700 bg-dark text-primary"
                       @checked(in_array($amenity, $selectedItems))>
                {{ $amenity }}
            </label>
            @endforeach
        </div>
        @error('amenities')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Image --}}
    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-slate-300 mb-2">Room Image</label>
        @if($isEdit && $room->image)
        <div class="mb-3 flex items-center gap-4">
            <img src="{{ $room->image_url }}" class="w-20 h-20 object-cover rounded-lg border border-purple-700">
            <p class="text-slate-400 text-xs">Current image. Upload a new file below to replace it.</p>
        </div>
        @endif
        <input type="file" name="image" accept="image/*"
               class="w-full text-slate-300 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-purple-900/60 file:text-purple-300 hover:file:bg-purple-900" />
        <p class="text-slate-500 text-xs mt-1">JPG, PNG or WebP. Max 2MB.</p>
        @error('image')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Availability --}}
    <div class="sm:col-span-2">
        <label class="flex items-center gap-3 cursor-pointer">
            <input type="hidden" name="is_available" value="0">
            <input type="checkbox" name="is_available" value="1"
                   class="w-5 h-5 rounded border-purple-700 bg-dark text-primary"
                   @checked(old('is_available', $room->is_available ?? true))>
            <div>
                <p class="text-slate-300 text-sm font-medium">Mark as Available</p>
                <p class="text-slate-500 text-xs">Uncheck to hide this room from public booking</p>
            </div>
        </label>
    </div>
</div>
