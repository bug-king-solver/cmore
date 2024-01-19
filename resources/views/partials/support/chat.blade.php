@php
    $zendeskEnabled = config('app.zendesk.enabled');
    $zendeskToken = config('app.zendesk.token');
@endphp

@if ($zendeskEnabled)
    <script nonce="{{ csp_nonce() }}" id="ze-snippet"
        src="https://static.zdassets.com/ekr/snippet.js?key={{ $zendeskToken }}"></script>

    <script type="text/javascript" nonce="{{ csp_nonce() }}">
        window.zESettings = {
            webWidget: {
                color: {
                    theme: '{{ color(6) }}',
                    launcher: '{{ color(6) }}', // This will also update the badge
                    launcherText: '{{ color(4) }}',
                    button: '{{ color(6) }}',
                    resultLists: '{{ color(5) }}',
                    header: '{{ color(6) }}',
                    articleLinks: '{{ color(5) }}'
                }
            }
        };

        zE(function() {
            @if (!auth()->user())
                zE.setLocale('en');
            @else
                zE.setLocale('{{ substr(auth()->user()->locale, 0, 2) }}');
                zE.identify({
                    name: '{{ auth()->user()->name }}',
                    email: '{{ auth()->user()->email }}',
                    organization: '{{ request()->routeIs('tenant.*') ? tenant()->id : 'central' }}'
                });
            @endif
        });
    </script>
@endif
