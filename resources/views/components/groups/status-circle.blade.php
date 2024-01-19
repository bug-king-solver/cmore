@php
    switch ($status) {
        case 'not-started':
            $color = '#ff0000';
            $title = __('Not started');
            break;
        case 'ongoing':
            $color = '#ffa500';
            $title = __('Ongoing');
            break;
        case 'completed':
            $color = '#008000';
            $title = __('Completed');
            break;
    }
@endphp
<div>
    <div class="w-4 h-4 rounded-full bg-[{{ $color }}]" data-tooltip-target="tooltip-target-status-{{$color}}" data-tooltip-trigger="hover" >
    </div>
    <div id="tooltip-target-status-{{$color}}" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
        {{ $title ?? '' }}
        <div class="tooltip-arrow" data-popper-arrow></div>
    </div>
</div>
