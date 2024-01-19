<?php

return [
    /**
     * Base URL
     */
    'base_url' => env('DOCUSIGN_BASE_URL', null),

    /**
     * Account URL
     */
    'account_url' => env('DOCUSIGN_ACCOUNT_URL', null),

    /**
     * API URL
     */
    'api_url' => env('DOCUSIGN_API_URL', null),

    /**
     * Monitor URL
     */
    'monitor_url' => env('DOCUSIGN_MONITOR_URL', null),

    /**
     * Integration key (client ID)
     */
    'client_id' => env('DOCUSIGN_CLIENT_ID', null),

    /**
     * Impersonated user ID
     */
    'user_id' => env('DOCUSIGN_USER_ID', null),

    /**
     * Account ID
     */
    'account_id' => env('DOCUSIGN_ACCOUNT_ID', null),

    /**
     * Organization ID
     */
    'organization_id' => env('DOCUSIGN_ORGANIZATION_ID', null),

    /**
     * Refresh access token automatically, when it is expired
     */
    'refresh_token_automatically' => env('DOCUSIGN_REFRESH_TOKEN_AUTOMATICALLY', false),

    /**
     * Private key path
     */
    'private_key_path' => env('DOCUSIGN_PRIVATE_KEY', false),

];
