<div>
    <x-slot name="header">
        <x-header title="{{ $documentHeading }}" data-test="library-header" click="{{ route('tenant.library.index') }}">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>

    <div class="px-4 lg:px-0 mt-8">
        <div class="mx-auto max-w-7xl sm:px-6 px-4 lg:px-0 leading-normal mb-14">
            <p class="font-semibold text-esg5 font-encodesans text-sm mb-8 uppercase">{{ __($title) }}</p>
            @if(count($documents) > 0)
                <div class="overflow-hidden overflow-x-auto">
                    <x-tables.bordered.table aria-describedby="{{ $documentHeading }}">
                        <x-slot name="thead">
                            <x-tables.bordered.th class="w-6/12">{{ __('Title') }}</x-tables.bordered.th>
                            <x-tables.bordered.th>{{ __('Size') }}</x-tables.bordered.th>
                            @can('library.view')
                                <x-tables.bordered.th>&nbsp;</x-tables.bordered.th>
                            @endcan
                        </x-slot>
                        @foreach($documents as $document)
                            <x-tables.bordered.tr class="text-esg8 hover:bg-gray-100">
                                <x-tables.bordered.td>
                                    <a target="_blank" class="flex items-center hover:text-esg6" href="{{ tenantPrivateAsset($document, 'library') }}">
                                        <div class="mr-3">
                                            @include('icons/library/file')
                                        </div>
                                        {{ basename($document) }}
                                    </a>
                                </x-tables.bordered.td>
                                <x-tables.bordered.td>{{ number_format(Storage::disk($disk)->size($document) / 1024, 2) . ' KB' }}</x-tables.bordered.td>
                                <x-tables.bordered.td>
                                    <div class="flex w-full items-center justify-end space-x-4">
                                        @can('library.view')
                                            @if(tenant()->onActiveSubscription)
                                                <x-buttons.a-icon class="inline py-1.5 px-2 text-sm" download href="{{ privateAssetDownload($document, 'library') }}">
                                                    @include('icons/tables/download')
                                                </x-buttons.a-icon>
                                            @else
                                                <x-buttons.a-icon class="inline py-1.5 px-2 text-sm" @click="trial_modal = true">
                                                    @include('icons/tables/download')
                                                </x-buttons.a-icon>
                                            @endif
                                        @endcan
                                    </div>
                                </x-tables.bordered.td>

                            </x-tables.bordered.tr>
                        @endforeach
                    </x-tables-bordered-table>
                </div>
            @else
            <div class="flex my-28 items-center justify-center">
                <div class="text-esg6 text-center text-xs">
                    <p class="font-bold mb-2">{{ __('This folder is empty') }}</p>
                </div>
            </div>
            @endif
        </div>

    </div>
</div>
