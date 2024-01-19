<div class="p-10">
    @if (tenant()->login_logo)
        <img alt="{{ tenant()->name }}" src="{{ asset(tenant()->login_logo) }}" class="max-w-[288px] max-h-40" />
    @else
        @include('logos/logo3', ['width' => '287.69px', 'height' => '150px'])
    @endif
</div>
