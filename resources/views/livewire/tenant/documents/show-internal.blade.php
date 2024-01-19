<div>
<x-slot name="header">
    <x-header title="{{ tenant('company') }}" data-test="library-header" click="{{ route('tenant.library.index') }}">
        <x-slot name="left"></x-slot>
        <x-slot name="right">
            @if(auth()->user()->type == 'internal' && auth()->user()->can('library.create'))
                @php
                $attachdata = json_encode(["model" => null, "modelType" => null, "internalAttachment"=>true]);
                @endphp
                <div class="flex items-center justify-end space-x-2">
                    <x-dropdown.library>
                        <li>
                            <x-buttons.btn-text modal="documents.folder.modals.form" class="text-gray-700 w-full py-2 px-2 hover:bg-gray-100 inline-flex items-center">
                                @include('icons/library/plus-folder', ['class' => 'mr-2']) {{ __('New Folder') }}
                            </x-buttons.btn-text>
                        </li>
                        <li>
                            <x-buttons.btn-text
                            modal="modals.attachments"
                            :data="$attachdata"
                            class="text-gray-700 w-full py-2 px-2 hover:bg-gray-100 inline-flex items-center">
                                @include('icons/library/upload', ['class' => 'mr-2']) {{ __('Upload') }}
                            </x-buttons.btn-text>
                        </li>
                    </x-dropdown.library>
                </div>
            @endif
        </x-slot>
    </x-header>
</x-slot>

<div class="px-4 lg:px-0 mt-8">
    <div class="mx-auto max-w-7xl sm:px-6 px-4 lg:px-0 leading-normal">
        <p class="font-semibold text-esg5 text-sm mb-4 pl-6 mt-14">{{ __('Folders') }}</p>
        <div class="overflow-hidden overflow-x-auto">
            <x-tables.bordered.table aria-describedby="{{ __('Folders') }}">
                <x-slot name="thead">
                    <x-tables.bordered.th class="w-6/12">{{ __('Name') }}</x-tables.bordered.th>
                    <x-tables.bordered.th class="w-1/6">{{ __('Size') }}</x-tables.bordered.th>
                    <x-tables.bordered.th>{{ __('Updated') }}</x-tables.bordered.th>
                    <x-tables.bordered.th>&nbsp;</x-tables.bordered.th>
                </x-slot>
                @if($folders->count() > 0)
                @foreach($folders as $folder)
                    @php
                        $fullSlug = $folder->slug;
                    @endphp
                    <x-tables.bordered.tr class="text-esg8 hover:bg-gray-100">
                        <x-tables.bordered.td>
                            <a class="flex items-center hover:text-esg6" href="{{ route('tenant.library.folder.show', ['slug' => $folder->slug]) }}">
                                <div class="mr-3">
                                    @include('icons/library/folder')
                                </div>
                                {{$folder->name}}
                            </a>
                        </x-tables.bordered.td>
                        <x-tables.bordered.td>
                            {{$folder->totalFiles}} {{__('Files')}}
                        </x-tables.bordered.td>
                        <x-tables.bordered.td>
                            {{ $folder->updated_at }}
                        </x-tables.bordered.td>

                        <x-tables.bordered.td class="flex w-full items-center justify-end " >
                            @php $data = json_encode(["documentFolder" => $folder->id]); @endphp
                            @if(auth()->user()->type == 'internal')
                                @can('library.update')

                                    <x-buttons.btn-icon modal="documents.folder.modals.form" :data="$data" class="text-esg8">
                                        <x-slot name="buttonicon">
                                            @include('icons/tables/edit')
                                        </x-slot>
                                    </x-buttons.btn-icon>
                                @endcan
                                @can('library.delete')
                                    <x-buttons.btn-icon modal="documents.folder.modals.delete" :data="$data">
                                        <x-slot name="buttonicon">
                                            @include('icons/tables/delete')
                                        </x-slot>
                                    </x-buttons.btn-icon>
                                @endcan
                            @endif
                        </x-tables.bordered.td>
                    </x-tables.bordered.tr>
                @endforeach
                @else
                    <x-tables.bordered.tr class="text-esg8 hover:bg-gray-100">
                        <x-tables.bordered.td colspan="4" class="text-center">
                            {{ __('No Folders Found') }}
                        </x-tables.bordered.td>
                    </x-tables.bordered.tr>
                @endif
            </x-tables-bordered-table>
        </div>
        @if($folders->hasMorePages())
            <x-buttons.btn-loadmore wire:click.prevent="loadMoreFolders">
                <x-slot name="buttonicon">
                    @include('icons/library/loadmore')
                </x-slot>
                {{__('Load more')}}
            </x-buttons.btn-icon-text>
        @endif

        <p class="font-semibold text-esg5 text-sm mb-4 pl-6 mt-14">{{ __('All Files') }}</p>
        <div class="overflow-hidden overflow-x-auto">
            <x-tables.bordered.table aria-describedby="{{ __('All Files') }}">
                <x-slot name="thead">
                    <x-tables.bordered.th class="w-6/12">{{ __('Title') }}</x-tables.bordered.th>
                    <x-tables.bordered.th>{{ __('Size') }}</x-tables.bordered.th>
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
                            <a target="_blank" class="flex items-center hover:text-esg6" href="{{ tenantPrivateAsset($document->id, 'attachments') }}">
                                <div class="mr-3">
                                    @include('icons/library/file')
                                </div>
                                {{$document->name}}
                            </a>
                        </x-tables.bordered.td>
                        <x-tables.bordered.td>{{ number_format($document->size / 1024, 2) . ' KB'; }}</x-tables.bordered.td>
                        <x-tables.bordered.td>{{ $document->created_at }}</x-tables.bordered.td>
                        <x-tables.bordered.td>
                            <div class="flex w-full items-center justify-end space-x-4">
                                @can('library.view')
                                    @if(tenant()->onActiveSubscription)
                                        <x-buttons.a-icon class="inline py-1.5 px-2 text-sm" download="{{ $document->name }}" href="{{ privateAssetDownload($document->id, 'attachments') }}">
                                            @include('icons/tables/download')
                                        </x-buttons.a-icon>
                                    @else
                                        <x-buttons.a-icon class="inline py-1.5 px-2 text-sm" @click="trial_modal = true">
                                            @include('icons/tables/download')
                                        </x-buttons.a-icon>
                                    @endif
                                @endcan
                                @if(auth()->user()->type == 'internal')
                                    @can('library.delete')
                                        <x-buttons.btn-icon modal="modals.media-delete" :data="$data">
                                            <x-slot name="buttonicon">
                                                @include('icons/tables/delete')
                                            </x-slot>
                                        </x-buttons.btn-icon>
                                    @endcan
                                @endif
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
</div>
