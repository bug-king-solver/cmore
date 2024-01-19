<?php

return [
    'key' => env('TRANSLATIONIO_KEY', ''),
    'source_locale' => 'en',
    'target_locales' => ['fr', 'pt-BR', 'pt-PT', 'es'],

    /* Directories to scan for Gettext strings */
    'gettext_parse_paths' => ['app', 'resources'],
    'ignored_key_prefixes' => [
    ],
];
