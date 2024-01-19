
<div x-data="{ tab: false }">
    <ul class="flex flex-wrap text-sm font-medium text-center mt-10" id="myTab" data-tabs-toggle="#myTabContent" role="tablist">
    <!--
        <li>
            <a href="#" class="inline-block text-esg5" id="service-tab" data-tabs-target="#service" type="button" role="tab" aria-controls="service" aria-selected="false"
                :class="! tab ? 'text-esg5' : 'text-esg8'"
                x-on:click="tab = !tab">
                    <div class="flex items-center px-14 py-3 rounded-md gap-3"
                        :class="! tab ? 'bg-esg5/10 border border-esg5' : ''">
                    <div x-show="tab == false">
                        @include('icons.product.tab2', ['color' => color(5)])
                    </div>
                    <div x-show="tab == true">
                        @include('icons.product.tab2')
                    </div>

                    <span class="text-sm "> {{ __('Services generated') }} </span>
                </div>
            </a>
        </li>
    -->
        <li>
            <a href="#" class="inline-block text-esg5" id="list-tab" data-tabs-target="#list" type="button" role="tab" aria-controls="list" aria-selected="true"
                :class="!tab ? 'text-esg5' : 'text-esg8'"
                x-on:click="tab = !tab">
                <div class="flex items-center px-14 py-3 rounded-md gap-3"
                    :class="!tab ? 'bg-esg5/10 border border-esg5' : ''">
                    <div x-show="tab == false">
                        @include('icons.product.tab1', ['color' => color(8)])
                    </div>

                    <div x-show="tab == true">
                        @include('icons.product.tab1')
                    </div>

                    <span class="text-sm"> {{ __('Product list') }} </span>
                </div>
            </a>
        </li>
    </ul>

    <div id="myTabContent" class="mt-6">

        {{-- Panel tab --}}
        <div class="hidden" id="service" role="tabpanel" aria-labelledby="service-tab">
            @include('livewire.tenant.catalog.panel')
        </div>

        {{-- Products tab --}}
        <div class="hidden" id="list" role="tabpanel" aria-labelledby="list-tab">
            @include('livewire.tenant.catalog.products')
        </div>
    </div>
</div>
