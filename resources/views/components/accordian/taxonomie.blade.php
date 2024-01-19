@props([
    'title' => '',
    'titleclass' => '',
    'icon' => '',
    'defaulthide' => false,
])

<div class="accordion-item w-full p-3 border border-esg6/10 rounded-md delay-100 duration-500 overflow-hidden">

    <div class="accordion-header flex items-center justify-between">
        <div class="flex items-center gap-5">
            @if (isset($icon))
                <div class="">
                    {{ $icon ?? '' }}
                </div>
            @endif

            <div class="text-sm font-bold text-esg5 uppercase {{ $titleclass ?? '' }}">{{ $title ?? '' }}</div>
        </div>

        <div class="accordion-button cursor-pointer py-2 rounded-md px-2 border border-esg6/10">
            <span>@include('icons.according.hide')</span>
            <span >@include('icons.according.show')</span>
        </div>
    </div>

    <div class="accordion-content transition duration-500 mt-2 p-2" >
        {{ $slot ?? '' }}
    </div>
</div>
