<dropdown-trigger class="flex h-9 items-center">
    @isset($user->email)
        <img
            src="https://secure.gravatar.com/avatar/{{ md5(\Illuminate\Support\Str::lower($user->email)) }}?size=512"
            class="mr-3 h-8 w-8 rounded-full"
        />
    @endisset

    <span class="text-90">
        {{ $user->name ?? $user->email ?? __('Nova User') }}
    </span>
</dropdown-trigger>

<dropdown-menu slot="menu" width="200" direction="rtl">
    <ul class="list-reset">
        <li>
            <a href="{{ route('nova.logout') }}" class="text-90 hover:bg-30 block p-3 no-underline">
                {{ __('Logout') }}
            </a>
        </li>
    </ul>
</dropdown-menu>
