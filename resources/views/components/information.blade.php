@if ($slot->isNotempty())
    <div class="ml-2 inline">
        <button data-tooltip-target="tooltip-{{ $id }}" type="button" class=""
            @if (isset($model) && $model == true) x-init="$nextTick(() => { fireTooltip();})" @endif>@include('icons.info')
        </button>

        <div id="tooltip-{{ $id }}" role="tooltip"
            class="absolute z-10 max-w-md 0 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
            {{ $slot }}
            <div class="tooltip-arrow" data-popper-arrow></div>
        </div>
    </div>
@endif
