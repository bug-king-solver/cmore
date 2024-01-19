@props(['class' => ''])

<div id="footer"
    class="flex justify-center items-center font-normal text-xs text-esg16 border-t-[1px] px-4 py-5 sm:px-16 print:hidden mt-8 border-esg7 {{ $class }}">
    C-MORE © {{ date('Y') }}, {{ __('All rights reserved.') }}
</div>
