<div class="p-10">
    @if (tenant()->logo)
        <img alt="{{ tenant()->name }}" src="{{ asset(tenant()->logo) }}" class="max-w-[200px] max-h-11" />
    @else
        @include('logos/cmore-new-logo', ['width' => '287.69px', 'height' => '150px'])
    @endif
</div>
