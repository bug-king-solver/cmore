<div class="absolute right-0 w-24 h-[15px]">
    <div x-data="{ expanded: false }" class=" p-0.5 px-3">
        <a href="#" @click="expanded = ! expanded" class="text-esg11 text-sm">
            {{ __('Languages') }}
            <div x-show="!expanded" class="float-right my-3 ml-1">
                @include('icons.arrow-menu')
            </div>
            <div x-show="expanded" class="float-right my-3 ml-1">
                @include('icons.arrow-up')
            </div>
        </a>

        <div x-show="expanded" class="">
            @foreach (config('app.locales') as $locale)
                <a href="{{ route('tenant.locale', ['locale' => $locale]) }}"
                    class="text-esg11 block px-4 py-1 text-sm font-medium">{{ str_replace('_', '-', $locale) }}</a>
            @endforeach
        </div>
    </div>
</div>
