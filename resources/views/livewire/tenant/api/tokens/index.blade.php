<div>
    <x-slot name="header">
        <x-header title="{{ __('API`s Tokens') }}">
            <x-slot name="left"></x-slot>
        </x-header>
    </x-slot>

    <div class="flex flex-row justify-between gap-10 w-full">
        <div class="font-bold text-gray-700 text-lg">
            <b>{{ __('X-Tenant') }}</b> ( Tenant secret key ) : {{ tenant()->id }}
        </div>
        @can('api-tokens.create')
            <div>
                <x-buttons.add modal="api.tokens.modals.form" />
            </div>
        @endcan
    </div>

    <hr class="mb-10">

    <x-tables.table>

        <x-slot name="thead">
            <x-tables.th>{{ __('Id') }}</x-tables.th>
            <x-tables.th>{{ __('Name') }}</x-tables.th>
            <x-tables.th>{{ __('Abilities') }}</x-tables.th>
            <x-tables.th>{{ __('Last time used') }}</x-tables.th>
            <x-tables.th>&nbsp;</x-tables.th>
        </x-slot>

        @foreach ($this->tokens as $token)
            @php $loopEncoded = json_encode($loop); @endphp
            <x-tables.tr>
                <x-tables.td loop="{!! $loopEncoded !!}">{{ $token->id }}</x-tables.td>
                <x-tables.td loop="{!! $loopEncoded !!}">{{ $token->name }}</x-tables.td>
                <x-tables.td loop="{!! $loopEncoded !!}">{!! implodeArray($token->abilities) !!}</x-tables.td>
                <x-tables.td loop="{!! $loopEncoded !!}">{{ $token->last_used_at ?? '-----' }}</x-tables.td>
                <x-tables.td loop="{!! $loopEncoded !!}">
                    <div class="flex w-full items-center justify-end space-x-4">
                        @php $data = json_encode(["token" => $token->id]); @endphp
                        @can('api-tokens.update')
                            <x-buttons.edit modal="api.tokens.modals.form" :data="$data" />
                        @endcan
                        @can('api-tokens.delete')
                            <x-buttons.trash modal="api.tokens.modals.delete" :data="$data" />
                        @endcan
                    </div>
                </x-tables.td>
            </x-tables.tr>
        @endforeach
    </x-tables.table>

    <div class="">
        {{ $this->tokens->links() }}
    </div>
</div>
