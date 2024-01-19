<div>
    <x-modals.confirm click="delete" :title="$title" :question="$question"
        extra="{{ $extra ?? __('(This action is irreversible and all data will be erased.)') }}">
        {{ $slot ?? null }}
    </x-modals.confirm>
</div>
