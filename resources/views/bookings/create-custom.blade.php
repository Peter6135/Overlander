@extends('layouts.app')

@section('title', 'Custom Trip Request — Overlander')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ __('booking.custom_title') }}</h1>
        <p class="text-gray-500 mt-1">{{ __('booking.custom_subtitle') }}</p>
    </div>

    <form method="POST" action="{{ route('bookings.store-custom') }}" class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">
        @csrf

        @if($destinations->isNotEmpty())
        <div>
            <div class="flex items-baseline justify-between gap-3">
                <label class="block text-sm font-medium text-gray-700">{{ __('booking.custom_places_label') }}</label>
                <span id="places-count" class="text-xs font-medium text-brand-600" data-template="{{ __('booking.custom_places_selected') }}"></span>
            </div>
            <p class="text-xs text-gray-400 mt-0.5">{{ __('booking.custom_places_hint') }}</p>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mt-3">
                @foreach($destinations as $destination)
                <label class="relative cursor-pointer">
                    <input type="checkbox" name="destinations[]" value="{{ $destination->id }}" class="place-input peer sr-only" data-id="{{ $destination->id }}" data-name="{{ $destination->name }}"
                           @checked(in_array($destination->id, array_map('intval', old('destinations', []))))>
                    <div class="rounded-xl overflow-hidden border-2 border-gray-200 bg-white transition-all duration-200 hover:border-brand-300 peer-checked:border-brand-500 peer-checked:shadow-md peer-focus-visible:ring-2 peer-focus-visible:ring-brand-300">
                        <img src="{{ $destination->cover_photo_url }}" alt="{{ $destination->name }}" class="w-full h-24 object-cover">
                        <div class="p-2">
                            <p class="text-xs font-semibold text-gray-800 leading-tight">{{ $destination->name }}</p>
                            <p class="text-[11px] text-gray-400 mt-0.5">{{ $destination->location }}</p>
                        </div>
                    </div>
                    <span class="place-badge absolute top-2 right-2 w-6 h-6 rounded-full bg-brand-500 text-white text-xs font-bold items-center justify-center shadow hidden peer-checked:flex" data-id="{{ $destination->id }}">✓</span>
                </label>
                @endforeach
            </div>
            <div id="route-box" class="hidden mt-4 rounded-xl border border-brand-200 bg-brand-50/50 p-4"
                 data-up="{{ __('booking.custom_move_up') }}" data-down="{{ __('booking.custom_move_down') }}" data-remove="{{ __('booking.custom_remove') }}" data-drag="{{ __('booking.custom_drag') }}">
                <p class="text-xs font-semibold text-brand-700 mb-2">{{ __('booking.custom_route_title') }}</p>
                <ol id="route-list" class="space-y-2"></ol>
                <p class="text-[11px] text-gray-400 mt-2">{{ __('booking.custom_route_hint') }}</p>
            </div>
            <div id="route-inputs"></div>
            @error('destinations') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
            @error('destinations.*') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
        </div>
        @endif

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ $destinations->isNotEmpty() ? __('booking.custom_or_write') : __('booking.custom_details_label') }}
            </label>
            <textarea name="custom_request" rows="5" placeholder="{{ __('booking.custom_details_placeholder') }}"
                      class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">{{ old('custom_request') }}</textarea>
            @error('custom_request') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid sm:grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('booking.estimated_date') }} <span class="text-red-500">*</span></label>
                <input type="date" name="trip_date" value="{{ old('trip_date') }}" min="{{ now()->addDay()->toDateString() }}" required
                       class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('booking.travelers') }} <span class="text-red-500">*</span></label>
                <input type="number" name="pax" min="1" max="20" value="{{ old('pax', 1) }}" required
                       class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
            </div>
        </div>

        <div class="border-t border-gray-100 pt-4">
            <p class="text-sm font-medium text-gray-700 mb-3">{{ __('booking.contact_detail') }}</p>
            <div class="grid sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">{{ __('booking.full_name') }}</label>
                    <input type="text" name="guest_name" value="{{ old('guest_name', auth()->user()->name) }}" required
                           class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">{{ __('booking.email') }}</label>
                    <input type="email" name="guest_email" value="{{ old('guest_email', auth()->user()->email) }}" required
                           class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs text-gray-500 mb-1">{{ __('booking.phone_number') }}</label>
                    <input type="text" name="guest_phone" value="{{ old('guest_phone', auth()->user()->phone) }}" required
                           class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
                </div>
            </div>
        </div>

        <p class="text-xs text-gray-500 bg-gray-50 rounded-xl p-3">{{ __('booking.whatsapp_followup_note') }}</p>

        <button type="submit" class="btn-pop w-full px-6 py-3 bg-brand-500 text-white rounded-xl font-semibold hover:bg-brand-600 transition-colors">
            {{ __('booking.send_request') }}
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const inputs = Array.from(document.querySelectorAll('.place-input'));
    const counter = document.getElementById('places-count');
    const box = document.getElementById('route-box');
    const list = document.getElementById('route-list');
    const hidden = document.getElementById('route-inputs');
    if (! inputs.length || ! box) return;

    const byId = Object.fromEntries(inputs.map(i => [i.dataset.id, i]));
    const labels = { up: box.dataset.up, down: box.dataset.down, remove: box.dataset.remove, drag: box.dataset.drag };

    // Starts with whatever is already ticked (e.g. after a validation error), in page order.
    let route = inputs.filter(i => i.checked).map(i => i.dataset.id);

    // From here JS owns the submitted order, so the plain checkboxes stop submitting.
    inputs.forEach(i => i.removeAttribute('name'));

    function button(text, label, disabled, onClick) {
        const b = document.createElement('button');
        b.type = 'button';
        b.textContent = text;
        b.setAttribute('aria-label', label);
        b.title = label;
        b.disabled = disabled;
        b.className = 'w-7 h-7 rounded-lg border border-gray-200 bg-white text-gray-500 text-xs hover:border-brand-300 hover:text-brand-600 transition-colors disabled:opacity-30 disabled:hover:border-gray-200 disabled:hover:text-gray-500';
        b.addEventListener('click', onClick);
        return b;
    }

    function render() {
        inputs.forEach(i => { i.checked = route.includes(i.dataset.id); });
        document.querySelectorAll('.place-badge').forEach(b => {
            const pos = route.indexOf(b.dataset.id);
            b.textContent = pos >= 0 ? pos + 1 : '✓';
        });

        counter.textContent = route.length ? counter.dataset.template.replace(':n', route.length) : '';
        box.classList.toggle('hidden', route.length === 0);

        list.innerHTML = '';
        route.forEach((id, idx) => {
            const li = document.createElement('li');
            li.className = 'flex items-center gap-2 bg-white rounded-lg border border-gray-100 px-2 py-2 transition-shadow';
            li.dataset.id = id;

            const handle = document.createElement('span');
            handle.textContent = '⋮⋮';
            handle.title = labels.drag;
            handle.setAttribute('aria-label', labels.drag);
            handle.className = 'w-6 shrink-0 text-center text-gray-300 hover:text-brand-500 cursor-grab active:cursor-grabbing select-none touch-none leading-none';
            handle.addEventListener('pointerdown', e => startDrag(e, li, handle));

            const num = document.createElement('span');
            num.className = 'w-6 h-6 shrink-0 rounded-full bg-brand-500 text-white text-xs font-bold flex items-center justify-center';
            num.textContent = idx + 1;

            const name = document.createElement('span');
            name.className = 'flex-1 text-sm text-gray-800';
            name.textContent = byId[id].dataset.name;

            li.append(handle, num, name,
                button('↑', labels.up, idx === 0, () => move(idx, -1)),
                button('↓', labels.down, idx === route.length - 1, () => move(idx, 1)),
                button('×', labels.remove, false, () => { route.splice(idx, 1); render(); }));
            list.appendChild(li);
        });

        hidden.innerHTML = '';
        route.forEach(id => {
            const h = document.createElement('input');
            h.type = 'hidden';
            h.name = 'destinations[]';
            h.value = id;
            hidden.appendChild(h);
        });
    }

    function startDrag(e, li, handle) {
        if (e.pointerType === 'mouse' && e.button !== 0) return;
        e.preventDefault();

        try { handle.setPointerCapture(e.pointerId); } catch (err) { /* window listeners below still track the pointer */ }
        li.classList.add('shadow-lg', 'ring-2', 'ring-brand-300', 'relative', 'z-10');

        const onMove = ev => {
            const others = Array.from(list.children).filter(x => x !== li);
            const next = others.find(x => {
                const r = x.getBoundingClientRect();
                return ev.clientY < r.top + r.height / 2;
            });

            if (next) {
                if (li.nextElementSibling !== next) list.insertBefore(li, next);
            } else if (list.lastElementChild !== li) {
                list.appendChild(li);
            }
        };

        const onEnd = () => {
            window.removeEventListener('pointermove', onMove);
            window.removeEventListener('pointerup', onEnd);
            window.removeEventListener('pointercancel', onEnd);
            route = Array.from(list.children).map(x => x.dataset.id);
            render();
        };

        window.addEventListener('pointermove', onMove);
        window.addEventListener('pointerup', onEnd);
        window.addEventListener('pointercancel', onEnd);
    }

    function move(idx, delta) {
        const target = idx + delta;
        [route[idx], route[target]] = [route[target], route[idx]];
        render();
    }

    inputs.forEach(i => i.addEventListener('change', () => {
        const id = i.dataset.id;
        route = i.checked ? [...route.filter(x => x !== id), id] : route.filter(x => x !== id);
        render();
    }));

    render();
})();
</script>
@endpush
