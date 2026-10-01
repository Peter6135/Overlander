@extends('layouts.app')

@section('title', 'Booking ' . $package->name . ' — Overlander')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ __('booking.show_trip_title') }}</h1>
        <p class="text-gray-500 mt-1">{{ $package->name }}</p>
    </div>

    <form method="POST" action="{{ route('bookings.store', $package) }}" class="bg-white rounded-2xl border border-gray-200 p-6 space-y-5">
        @csrf

        <div>
            <p class="block text-sm font-medium text-gray-700 mb-2">{{ __('booking.choose_plan') }} <span class="text-red-500">*</span></p>
            <div class="grid gap-3">
                @foreach($package->plans as $plan)
                <label class="flex items-center justify-between border rounded-xl p-3 cursor-pointer has-[:checked]:border-brand-400 has-[:checked]:bg-brand-50">
                    <span class="flex items-center gap-3">
                        <input type="radio" name="package_plan_id" value="{{ $plan->id }}"
                           @checked(old('package_plan_id', $cart['package_plan_id'] ?? null) == $plan->id || (! old('package_plan_id') && ! ($cart['package_plan_id'] ?? null) && $plan->is_recommended)) required>
                        <span class="text-sm font-medium text-gray-800">{{ $plan->name }}</span>
                    </span>
                    <span class="text-brand-600 font-bold text-sm">${{ number_format($plan->price, 0) }}</span>
                </label>
                @endforeach
            </div>
            @error('package_plan_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid sm:grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('booking.check_date') }} <span class="text-red-500">*</span></label>
                <input type="date" id="trip_date" name="trip_date" value="{{ old('trip_date') }}" min="{{ now()->addDay()->toDateString() }}" required
                       class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
                @error('trip_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                <p id="availability-msg" class="text-xs mt-1"></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('booking.travelers') }} <span class="text-red-500">*</span></label>
                <input type="number" id="pax" name="pax" min="1" max="{{ $package->capacity ?? 20 }}"
                       value="{{ old('pax', $cart['pax'] ?? 1) }}" required
                       class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
                @error('pax') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('booking.additional_message') }}</label>
            <textarea name="message" rows="3" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">{{ old('message') }}</textarea>
        </div>

        <p class="text-xs text-gray-500 bg-gray-50 rounded-xl p-3">{{ __('booking.whatsapp_followup_note') }}</p>

        <button type="submit" class="w-full px-6 py-3 bg-brand-500 text-white rounded-xl font-semibold hover:bg-brand-600 transition-colors">
            {{ __('booking.confirm_button') }}
        </button>
    </form>
</div>

@push('scripts')
<script>
(function () {
    const dateInput = document.getElementById('trip_date');
    const paxInput = document.getElementById('pax');
    const msgEl = document.getElementById('availability-msg');
    const availabilityUrl = '{{ route('packages.availability', $package) }}';
    const i18n = {
        fullyBooked: @json(__('booking.fully_booked_js')),
        slotsLeftLimited: @json(__('booking.slots_left_limited_js')),
        slotsLeft: @json(__('booking.slots_left_js')),
    };

    function checkAvailability() {
        if (! dateInput.value) { msgEl.textContent = ''; return; }

        fetch(availabilityUrl + '?date=' + dateInput.value)
            .then(res => res.json())
            .then(data => {
                if (data.remaining === null) { msgEl.textContent = ''; return; }

                if (data.remaining <= 0) {
                    msgEl.textContent = i18n.fullyBooked;
                    msgEl.className = 'text-xs mt-1 text-red-500';
                } else if (parseInt(paxInput.value || '1', 10) > data.remaining) {
                    msgEl.textContent = i18n.slotsLeftLimited.replace(':n', data.remaining);
                    msgEl.className = 'text-xs mt-1 text-red-500';
                } else {
                    msgEl.textContent = i18n.slotsLeft.replace(':n', data.remaining);
                    msgEl.className = 'text-xs mt-1 text-green-600';
                }
            });
    }

    dateInput?.addEventListener('change', checkAvailability);
    paxInput?.addEventListener('input', checkAvailability);
})();
</script>
@endpush
@endsection
