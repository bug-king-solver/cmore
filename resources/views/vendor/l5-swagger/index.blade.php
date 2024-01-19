<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ config('l5-swagger.documentations.' . $documentation . '.api.title') }}</title>
    <link rel="stylesheet" type="text/css" href="{{ l5_swagger_asset($documentation, 'swagger-ui.css') }}">

    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('docs/assets/theme-flat-blue.css') }}"> --}}
    <link nonce="{{ csp_nonce() }}" rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/5.3.1/swagger-ui.css"
        integrity="sha512-Ts9m3wxbwtw6JuNCKsMN8AbHHicAd9p065NG/0tswcyTIyFXSwt6Rvtl3tqKcpremZ3SqTXq37iWzEikVAjcVg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="icon" type="image/png" href="{{ l5_swagger_asset($documentation, 'favicon-32x32.png') }}"
        sizes="32x32" />
    <link rel="icon" type="image/png" href="{{ l5_swagger_asset($documentation, 'favicon-16x16.png') }}"
        sizes="16x16" />

    <style nonce="{{ csp_nonce() }}">
        html {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }

        *,
        *:before,
        *:after {
            box-sizing: inherit;
        }

        body {
            margin: 0;
            background: #fafafa;
        }

        hgroup h2 {
            display: flex;
            flex-direction: column;
        }

        hgroup h2 span {
            display: flex;
            flex-direction: row;
            gap: 10px;
            margin-top: 10px;
        }

        table.parameters td.parameters-col_description {
            padding-left: 1rem !important;
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
            width: 100% !important;
        }

        table.parameters td.parameters-col_description input {
            width: 80% !important;
            max-width: 100% !important;
        }

        /*hgroup a {
            display: none !important;
        }*/

        div[id^="model-"][data-name*="Response"] {
            display: none;
        }
    </style>
</head>

<body>

    <div id="swagger-ui"></div>

    {{-- <script nonce="{{ csp_nonce() }}" src="{{ l5_swagger_asset($documentation, 'swagger-ui-bundle.js') }}"></script>
    <script nonce="{{ csp_nonce() }}" src="{{ l5_swagger_asset($documentation, 'swagger-ui-standalone-preset.js') }}">
    </script> --}}
    {{-- <script nonce="{{ csp_nonce() }}"  src="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/5.3.1/swagger-ui.js" integrity="sha512-Ql/ia6Ea8ki9anXxMopqxI5ZAAtTH39Tqn3vSMUFp2f+RB6Vxu2zM2iL1xh81PnRb/mlNY17DJeFsfJBcxV+zQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}

    <script nonce="{{ csp_nonce() }}"
        src="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/5.3.1/swagger-ui-bundle.min.js"
        integrity="sha512-NbdHiATK9LUtXcNgCv79GFUQBMH7CWVvpqY/5vJ7mDe01r4S3tWuEiv9JgMo9YAvgD44Jvj47PAh39UdsdfdvA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script nonce="{{ csp_nonce() }}"
        src="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/5.3.1/swagger-ui-standalone-preset.min.js"
        integrity="sha512-RDbXNynicl6hPWkswyUgM5wGrJObNRebF0NQCivX4kJ8pSBsSRFPCWeXFdTnd42RX2oQ+wcJGRqugHot+RgrmQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script nonce="{{ csp_nonce() }}">
        window.HideEmptyTagsPlugin = function(isUserLogged = false) {
            const blockedTags = [
                'Tenant',
                'Reputational',
                'Reputation',
                'Document Analysis'
            ];

            return {
                statePlugins: {
                    spec: {
                        wrapSelectors: {
                            taggedOperations: (ori) => (...args) => {
                                return ori(...args)
                                    .filter((tagMeta, tagName) => {
                                        if (isUserLogged) {
                                            return tagMeta.get("operations") && tagMeta.get("operations").size >
                                                0
                                        } else {
                                            const words = tagName.split(" ");
                                            const isPresent = blockedTags.some(tag => {
                                                return words.some(word => tag.includes(word));
                                            });
                                            if (isPresent) {
                                                return false;
                                            }
                                            const operations = tagMeta.get("operations");
                                            return operations && operations.size > 0;
                                        }
                                    });
                            },
                            operationScheme: (ori) => (...args) => {

                            },
                        }
                    }
                }
            }
        }
    </script>

    <script nonce="{{ csp_nonce() }}">
        window.onload = async function() {

            async function getData() {
                const response = await fetch("{{ route('api.validate.interval-user') }}", {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                });
                const data = await response.json();
                const status = await response.status;
                return {
                    data,
                    status
                };
            }

            const {
                data,
                status
            } = await getData();

            // Build a system
            const ui = SwaggerUIBundle({
                dom_id: '#swagger-ui',
                url: "{!! $urlToDocs !!}",
                operationsSorter: {!! isset($operationsSorter) ? '"' . $operationsSorter . '"' : 'null' !!},
                configUrl: {!! isset($configUrl) ? '"' . $configUrl . '"' : 'null' !!},
                docExpansion: "{!! config('l5-swagger.defaults.ui.display.doc_expansion', 'none') !!}",
                filter: {!! config('l5-swagger.defaults.ui.display.filter') ? 'true' : 'false' !!},
                persistAuthorization: "{!! config('l5-swagger.defaults.ui.authorization.persist_authorization') ? 'true' : 'false' !!}",
                displayRequestDuration: true,
                showExtensions: true,
                deepLinking: true,
                syntaxHighlight: true,
                syntaxHighlight: {
                    activated: true,
                    theme: "arta",
                },
                hierarchicalTags: true,
                oauth2RedirectUrl: "{{ route('l5-swagger.' . $documentation . '.oauth2_callback', [], $useAbsolutePath) }}",
                layout: "StandaloneLayout",
                requestInterceptor: function(request) {
                    request.headers['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
                    return request;
                },
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset,
                ],
                plugins: [
                    SwaggerUIBundle.plugins.ErrIndex,
                    SwaggerUIBundle.plugins.DownloadUrl,
                    SwaggerUIBundle.plugins.SwaggerJsIndex,
                    HideEmptyTagsPlugin(status == 200 ? true : false),
                ],
                onComplete: function(swaggerApi, swaggerUi) {

                }
            });

            window.ui = ui;

            @if (in_array('oauth2', array_column(config('l5-swagger.defaults.securityDefinitions.securitySchemes'), 'type')))
                ui.initOAuth({
                    usePkceWithAuthorizationCodeGrant: "{!! (bool) config('l5-swagger.defaults.ui.authorization.oauth2.use_pkce_with_authorization_code_grant') !!}"
                });
            @endif
        }
    </script>
</body>

</html>
