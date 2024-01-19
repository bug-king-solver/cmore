<div>
    <x-modals.confirm click="restore" :title="$title" :question="$question" extra="{{ $extra ?? __('(This action will restore the data deleted)') }}" />
</div>
