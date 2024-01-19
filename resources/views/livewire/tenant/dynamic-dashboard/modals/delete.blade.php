<div>
    <x-modals.confirm-delete>
        <x-slot name="title">
            {{ __('Delete Dashboard') }}
        </x-slot>

        <x-slot name="question">
            {!! __('Do you want to delete the dashboard :strong":dashboard":cstrong?', ['dashboard' => $dashboard->name, 'strong' => '<strong>', 'cstrong' => '</strong>']) !!}
        </x-slot>
    </x-modals.confirm-delete>
</div>
