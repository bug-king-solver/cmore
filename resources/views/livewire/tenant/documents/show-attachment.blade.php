<div>
    <x-slot name="header">
        <x-header title="{{ __('Attachments') }}" data-test="library-header" click="{{ route('tenant.library.index') }}">
            <x-slot name="left"></x-slot>
            <x-slot name="right">
                @if(auth()->user()->type == 'internal' && auth()->user()->can('library.create'))
                    <div class="flex items-center justify-end space-x-2">

                        <x-buttons.btn-icon-text modal="modals.attachments">
                            <x-slot name="buttonicon">
                                @include('icons/tables/plus')
                            </x-slot>
                            {{ __('Upload') }}
                        </x-buttons.btn-icon-text>
                    </div>
                @endif
            </x-slot>
        </x-header>
    </x-slot>

    <div class="px-4 lg:px-0 mt-8">
    <div class="mx-auto max-w-7xl sm:px-6 px-4 lg:px-0 leading-normal">
        <div class="overflow-hidden overflow-x-auto mt-14">
            <x-tables.bordered.table aria-describedby="{{ __('All Files') }}">
                <x-slot name="thead">
                    <x-tables.bordered.th class="w-6/12">{{ __('Title') }}</x-tables.bordered.th>
                    <x-tables.bordered.th class="w-1/6">{{ __('Size') }}</x-tables.bordered.th>
                    <x-tables.bordered.th>{{ __('Upload at') }}</x-tables.bordered.th>
                    @can('library.view')
                        <x-tables.bordered.th>&nbsp;</x-tables.bordered.th>
                    @endcan
                </x-slot>
                @if(count($documents) > 0)
                @foreach($documents as $document)
                    @php $data = json_encode(["media" => $document->id]); @endphp
                    <x-tables.bordered.tr class="text-esg8 hover:bg-gray-100">
                        <x-tables.bordered.td>
                            <a target="_blank" class="flex items-center hover:text-esg6" href="{{ tenantPrivateAsset($document, 'attachments') }}">
                                <div class="mr-3">
                                    @include('icons/library/file')
                                </div>
                                {{$document->name}}
                            </a>
                        </x-tables.bordered.td>
                        <x-tables.bordered.td>{{ number_format($document->size / 1024, 2) . ' KB'; }}</x-tables.bordered.td>
                        <x-tables.bordered.td>{{ $document->created_at }}</x-tables.bordered.td>
                        <x-tables.bordered.td>
                            <div class="flex w-full items-center justify-end ">
                                @can('library.view')
                                    @if(tenant()->onActiveSubscription)
                                        <x-buttons.a-icon class="inline py-1.5 px-2 text-sm" download="{{$document->name}}" href="{{ privateAssetDownload($document, 'attachments') }}">
                                            @include('icons/tables/download')
                                        </x-buttons.a-icon>
                                    @else
                                        <x-buttons.a-icon class="inline py-1.5 px-2 text-sm" @click="trial_modal = true">
                                            @include('icons/tables/download')
                                        </x-buttons.a-icon>
                                    @endif
                                @endcan
                                @can('library.delete')
                                    <x-buttons.btn-icon modal="modals.media-delete" :data="$data">
                                        <x-slot name="buttonicon">
                                            @include('icons/tables/delete')
                                        </x-slot>
                                    </x-buttons.btn-icon>
                                @endcan
                            </div>
                        </x-tables.bordered.td>

                    </x-tables.bordered.tr>
                @endforeach
                @else
                <x-tables.bordered.tr class="text-esg8 hover:bg-gray-100">
                    <x-tables.bordered.td colspan="4" class="text-center">
                        {{ __('No Files Found') }}
                    </x-tables.bordered.td>
                </x-tables.bordered.tr>
                @endif
            </x-tables-bordered-table>
        </div>
        @if($documents->hasMorePages())
            <x-buttons.btn-loadmore wire:click.prevent="loadMoreDocuments">
                <x-slot name="buttonicon">
                    @include('icons/library/loadmore')
                </x-slot>
                {{__('Load more')}}
            </x-buttons.btn-icon-text>
        @endif
        <div class="mb-24"></div>
    </div>
</div>
