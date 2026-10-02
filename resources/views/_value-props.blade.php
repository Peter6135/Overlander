<div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
    @foreach([
        ['icon' => 'camera', 'label' => __('home.value_free_documentation'), 'sub' => __('home.value_free_documentation_sub')],
        ['icon' => 'badge', 'label' => __('home.value_no_charge'), 'sub' => __('home.value_no_charge_sub')],
        ['icon' => 'pin', 'label' => __('home.value_start_point'), 'sub' => __('home.value_start_point_sub')],
        ['icon' => 'sliders', 'label' => __('home.value_custom_journey'), 'sub' => __('home.value_custom_journey_sub')],
    ] as $value)
    <div class="text-center">
        <div class="w-14 h-14 mx-auto rounded-2xl bg-brand-50 flex items-center justify-center text-brand-500 mb-3 transition-all duration-300 hover:bg-brand-500 hover:text-white hover:scale-110 hover:shadow-lg hover:shadow-brand-500/30 hover:-translate-y-1">
            @switch($value['icon'])
                @case('camera')
                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 8h3l2-2h6l2 2h3a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V9a1 1 0 011-1z"/>
                        <circle cx="12" cy="13" r="3.5"/>
                    </svg>
                    @break
                @case('badge')
                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2l2.1 1.6 2.6-.4 1 2.4 2.4 1-.4 2.6L21 12l-1.6 2.1.4 2.6-2.4 1-1 2.4-2.6-.4L12 22l-2.1-1.6-2.6.4-1-2.4-2.4-1 .4-2.6L3 12l1.6-2.1-.4-2.6 2.4-1 1-2.4 2.6.4z"/>
                        <path d="M9 12l2 2 4-4"/>
                    </svg>
                    @break
                @case('pin')
                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 21s7-6.5 7-12a7 7 0 10-14 0c0 5.5 7 12 7 12z"/>
                        <circle cx="12" cy="9" r="2.5"/>
                    </svg>
                    @break
                @case('sliders')
                    <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 6h10M18 6h2M4 12h2M8 12h12M4 18h14"/>
                        <circle cx="14" cy="6" r="2"/>
                        <circle cx="6" cy="12" r="2"/>
                        <circle cx="18" cy="18" r="2"/>
                    </svg>
                    @break
            @endswitch
        </div>
        <p class="text-sm font-medium text-gray-700">{{ $value['label'] }}</p>
        <p class="text-xs text-gray-400 mt-0.5">{{ $value['sub'] }}</p>
    </div>
    @endforeach
</div>
