<div class="p-3">
    @if ($paginator->total() > (isset($this->itemsPerPage) ? $this->itemsPerPage : config('app.paginate.per_page')))
        @php(isset($this->numberOfPaginatorsRendered[$paginator->getPageName()]) ? $this->numberOfPaginatorsRendered[$paginator->getPageName()]++ : ($this->numberOfPaginatorsRendered[$paginator->getPageName()] = 1))

        <nav role="navigation" aria-label="Pagination Navigation" class="mt-5 flex items-center justify-between">
            <div class="flex flex-1 justify-between sm:hidden">
                <span>
                    @if ($paginator->onFirstPage())
                        <span
                            class="text-esg28 relative mx-2 inline-flex cursor-default select-none items-center px-1 py-1 text-sm font-medium leading-5">
                            {!! __('pagination.previous') !!}
                        </span>
                    @else
                        <button wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled"
                            dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before"
                            class="text-esg28 relative mx-2 inline-flex items-center px-1 py-1 text-sm font-medium leading-5">
                            {!! __('pagination.previous') !!}
                        </button>
                    @endif
                </span>

                <span>
                    @if ($paginator->hasMorePages())
                        <button wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled"
                            dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before"
                            class="text-esg28 relative mx-2 ml-3 inline-flex items-center px-1 py-1 text-sm font-medium leading-5">
                            {!! __('pagination.next') !!}
                        </button>
                    @else
                        <span
                            class="text-esg28 relative mx-2 ml-3 inline-flex cursor-default select-none items-center px-1 py-1 text-sm font-medium leading-5">
                            {!! __('pagination.next') !!}
                        </span>
                    @endif
                </span>
            </div>

            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                @if (isset($this->itemsPerPage))
                    <div>
                        <p class="text-esg16 text-sm leading-5">
                        <div>
                            <span class="text-esg16 mr-3 text-sm">{{ __('items per page') }}</span>
                            <select id="itemsPerPageSelect" wire:model="selectedItemsPerPage" wire:ignore wire:change="gotoPage(1)"
                                class="border-gray-300 border text-esg16 rounded py-1 px-2 text-sm focus:border-gray-400 w-fit min-w-[55px]">

                                @for ($i = 1; $i <= 3; $i++)
                                    <option value="{{ $this->itemsPerPage * $i }}"
                                        @if ($this->selectedItemsPerPage == $i) selected @endif>
                                        {{ $this->itemsPerPage * $i }}
                                    </option>
                                @endfor

                            </select>
                        </div>
                        </p>
                    </div>
                @else
                    <div>
                        <p class="text-esg28 text-sm leading-5">
                            <span>{!! __('Showing') !!}</span>
                            <span class="font-medium">{{ $paginator->firstItem() }}</span>
                            <span>{!! __('to') !!}</span>
                            <span class="font-medium">{{ $paginator->lastItem() }}</span>
                            <span>{!! __('of') !!}</span>
                            <span class="font-medium">{{ $paginator->total() }}</span>
                            <span>{!! __('results') !!}</span>
                        </p>
                    </div>
                @endif

                <div>
                    <span class="relative z-0 inline-flex text-sm text-esg16">
                        <span class="flex items-center">
                            {{-- Previous Page Link --}}
                            @if ($paginator->onFirstPage())
                                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                                    <span
                                        class="text-esg28 relative mx-2 inline-flex cursor-default items-center px-1 py-1 text-sm font-medium leading-5"
                                        aria-hidden="true">
                                        <svg width="10" height="10" viewBox="0 0 10 10" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M0.428711 9.57145V0.428589H1.57157V9.57145H0.428711ZM8.943 9.5143L4.48585 5.05716L8.943 0.600017L9.76204 1.41907L6.12395 5.05716L9.76204 8.69526L8.943 9.5143Z"
                                                fill="#B1B1B1" />
                                        </svg>
                                    </span>
                                </span>
                            @else
                                <button wire:click="gotoPage(1)" dusk="gotoPage1.after" rel="prev" wire:loading.attr="disabled"
                                    class="text-esg16 relative mx-2 inline-flex cursor-pointer items-center px-1 py-1 text-sm leading-5"
                                    aria-label="{{ __('pagination.previous') }}">
                                    <svg width="10" height="10" viewBox="0 0 10 10" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M0.428711 9.57145V0.428589H1.57157V9.57145H0.428711ZM8.943 9.5143L4.48585 5.05716L8.943 0.600017L9.76204 1.41907L6.12395 5.05716L9.76204 8.69526L8.943 9.5143Z"
                                            fill="#757575" />
                                    </svg>
                                </button>
                            @endif
                        </span>
                        <span class="flex items-center">
                            {{-- Previous Page Link --}}
                            @if ($paginator->onFirstPage())
                                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                                    <span
                                        class="text-esg28 relative mx-2 inline-flex cursor-default items-center px-1 py-1 text-sm font-medium leading-5"
                                        aria-hidden="true">
                                        <svg width="6" height="10" viewBox="0 0 6 10" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M4.68594 9.57146L0.095459 4.98098L4.68594 0.390503L5.50498 1.20955L1.73355 4.98098L5.50498 8.75241L4.68594 9.57146Z"
                                                fill="#B1B1B1" />
                                        </svg>
                                    </span>
                                </span>
                            @else
                                <button wire:click="previousPage('{{ $paginator->getPageName() }}')"
                                    dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                                    rel="prev"
                                    wire:loading.attr="disabled"
                                    class="text-esg16 relative mx-2 inline-flex cursor-pointer items-center px-1 py-1 text-sm leading-5"
                                    aria-label="{{ __('pagination.previous') }}">
                                    <svg width="6" height="10" viewBox="0 0 6 10" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M4.68594 9.57146L0.095459 4.98098L4.68594 0.390503L5.50498 1.20955L1.73355 4.98098L5.50498 8.75241L4.68594 9.57146Z"
                                            fill="#757575" />
                                    </svg>
                                </button>
                            @endif
                        </span>

                        <span class="pr-2"
                            wire:key="paginator-{{ $paginator->getPageName() }}-{{ $this->numberOfPaginatorsRendered[$paginator->getPageName()] }}-page{{ $paginator->currentPage() }}">
                            <span class="border-gray-300 border rounded p-2 m-2 py-1">
                                {{ $paginator->currentPage() }}
                            </span>
                            {{ __('of') }} &nbsp; {{ $paginator->lastPage() }}
                        </span>


                        <span class="flex items-center">
                            {{-- Next Page Link --}}
                            @if ($paginator->hasMorePages())
                                <button wire:click="nextPage('{{ $paginator->getPageName() }}')"
                                    dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                                    rel="next"
                                    wire:loading.attr="disabled"
                                    class="text-esg16 relative mx-2 inline-flex cursor-pointer items-centertext-sm leading-5"
                                    aria-label="{{ __('pagination.next') }}">
                                    <svg width="6" height="10" viewBox="0 0 6 10" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M0.999956 9.57146L0.180908 8.75241L3.95234 4.98098L0.180908 1.20955L0.999956 0.390503L5.59043 4.98098L0.999956 9.57146Z"
                                            fill="#757575" />
                                    </svg>
                                </button>
                            @else
                                <span class="inline-flex mx-2" aria-disabled="true"
                                    aria-label="{{ __('pagination.next') }}">
                                    <span
                                        class="text-esg28 relative -ml-px inline-flex cursor-default items-center px-2 py-1 text-sm font-medium leading-5"
                                        aria-hidden="true">
                                        <svg width="6" height="10" viewBox="0 0 6 10" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M0.999956 9.57146L0.180908 8.75241L3.95234 4.98098L0.180908 1.20955L0.999956 0.390503L5.59043 4.98098L0.999956 9.57146Z"
                                                fill="#B1B1B1" />
                                        </svg>
                                    </span>
                                </span>
                            @endif
                        </span>
                        <span class="flex items-center">
                            {{-- Next Page Link --}}
                            @if ($paginator->hasMorePages())
                                <button
                                    wire:click="gotoPage('{{ $paginator->lastPage() }}', '{{ $paginator->getPageName() }}')"
                                    dusk="lastPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                                    rel="next"
                                    wire:loading.attr="disabled"
                                    class="text-esg16 relative mx-2 inline-flex cursor-pointer items-center px-2 py-1 text-sm leading-5"
                                    aria-label="{{ __('pagination.last') }}">
                                    <svg width="10" height="10" viewBox="0 0 10 10" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M1.03804 9.47621L0.218994 8.65716L3.87614 5.00002L0.218994 1.34287L1.03804 0.523827L5.51423 5.00002L1.03804 9.47621ZM8.42852 9.57145V0.428589H9.57137V9.57145H8.42852Z"
                                            fill="#757575" />
                                    </svg>

                                </button>
                            @else
                                <span class="inline-flex mx-2" aria-disabled="true"
                                    aria-label="{{ __('pagination.next') }}">
                                    <span
                                        class="text-esg28 relative pr-1 inline-flex cursor-default items-center text-sm font-medium leading-5"
                                        aria-hidden="true">
                                        <svg width="10" height="10" viewBox="0 0 10 10" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M1.03804 9.47621L0.218994 8.65716L3.87614 5.00002L0.218994 1.34287L1.03804 0.523827L5.51423 5.00002L1.03804 9.47621ZM8.42852 9.57145V0.428589H9.57137V9.57145H8.42852Z"
                                                fill="#B1B1B1" />
                                        </svg>

                                    </span>
                                </span>
                            @endif
                        </span>
                    </span>
                </div>
            </div>
        </nav>
    @endif
</div>
