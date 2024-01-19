<style nonce="{{ csp_nonce() }}">
    @php
    $colors = tenant() ? tenant()->colors : [];

    if ($colors) {
        foreach ($colors as $name => $color) {
            if (! $color) continue;

            for ($i=5; $i<= 100; $i+=5) {
                echo "
                    .\!bg-{$name}\/{$i} {
                        background-color: " . hex2rgba($color, ($i / 100)) . " !important;
                    }

                    .bg-{$name}\/{$i} {
                        background-color: " . hex2rgba($color, ($i / 100)) . ";
                    }

                    .text-{$name}\/{$i} {
                        color:  " . hex2rgba($color, ($i / 100)) . ";
                    }

                    .\!text-{$name}\/{$i} {
                        color: " . hex2rgba($color, ($i / 100)) . " !important;
                    }

                    .\!border-{$name}\/{$i} {
                        border-color: " . hex2rgba($color, ($i / 100)) . " !important;
                    }

                    .border-{$name}\/{$i} {
                        border-color: " . hex2rgba($color, ($i / 100)) . ";
                    }

                    .hover\:bg-{$name}\/{$i}:hover {
                        background-color: " . hex2rgba($color, ($i / 100)) . ";
                    }

                    .hover\:\!bg-{$name}\/{$i}:hover {
                        background-color: " . hex2rgba($color, ($i / 100)) . " !important;
                    }

                    .\!hover\:bg-{$name}\/{$i}:hover {
                        background-color: " . hex2rgba($color, ($i / 100)) . " !important;
                    }

                    .hover\:border-{$name}\/{$i}:hover {
                        border-color: " . hex2rgba($color, ($i / 100)) . ";
                    }

                    .hover\:\!border-{$name}\/{$i}:hover {
                        border-color: " . hex2rgba($color, ($i / 100)) . " !important;
                    }

                    .\!hover\:border-{$name}\/{$i}:hover {
                        border-color: " . hex2rgba($color, ($i / 100)) . " !important;
                    }

                    .hover\:text-{$name}\/{$i}:hover {
                        color: " . hex2rgba($color, ($i / 100)) . ";
                    }

                    .hover\:\!text-{$name}\/{$i}:hover {
                        color: " . hex2rgba($color, ($i / 100)) . " !important;
                    }

                    .\!hover\:text-{$name}\/{$i}:hover {
                        color: " . hex2rgba($color, ($i / 100)) . " !important;
                    }
                ";
            }

            echo "

            .bg-{$name} {
                background-color: {$color};
            }

            .before\:bg-{$name}\/\[0\.75\]::before {
                background-color: {$color};
                opacity: .75;
            }

            .text-{$name} {
                color: {$color};
            }

            .\!text-{$name} {
                color: {$color};
            }

            .hover\:text-{$name}:hover {
                color: " . hex2rgba($color) . ";
            }

            .hover\:bg-{$name}:hover {
                background-color: " . hex2rgba($color) . ";
            }

            .\!border-{$name} {
                border-color: {$color} !important;
            }

            .hover\:border-{$name}:hover {
                border-color: {$color} !important;
            }

            .border-{$name} {
                border-color: {$color};
            }

            .border-y-{$name} {
                border-top-color: {$color};
                border-bottom-color: {$color};
            }

            .border-x-{$name} {
                border-left-color: {$color};
                border-left-color: {$color};
            }

            .border-t-{$name} {
                border-top-color: {$color};
            }

            .border-b-{$name} {
                border-bottom-color: {$color};
            }

            .border-l-{$name} {
                border-left-color: {$color};
            }

            .border-r-{$name} {
                border-right-color: {$color};
            }

            .divide-{$name} > :not([hidden]) ~ :not([hidden]) {
                border-color: {$color};
            }
            .focus\:ring-{$name}:focus {
                --tw-ring-color: {$color};
            }
            .focus\:border-{$name}:focus {
                border-color: {$color};
            }
            ";
        }
    }
    @endphp
</style>

<script nonce="{{ csp_nonce() }}">
        document.addEventListener('DOMContentLoaded', () => {
            @php
            if ($colors) {
                foreach ($colors as $name => $color) {
                    if (! $color) continue;
                    echo "
                    twConfig.theme.colors.{$name} = '{$color}';
                    ";
                }
            }
            @endphp
        });
    </script>
