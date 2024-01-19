<?php

/*
 *  Nova Two Factor config file
 *
 * */

return [

    'enabled' => env('BO_2FA_ENABLED', false),

    'user_table' => 'admins',

    'user_id_column' => 'id',

    /* Encrypt the google secret values saved in database */
    'encrypt_google2fa_secrets' => true,

    /* QR code can be generate using Google API or inbuilt 'BaconQrCode' package*/
    'use_google_qr_code_api' => false,

    'user_model' => \App\Models\Admin::class,

    /* Change visibility of Nova Two Fa menu in right sidebar */
    'showin_sidebar' => true,

    'menu_text' => 'Two FA',

    'menu_icon' => 'lock-closed',

    /* Exclude any routes from 2fa security */
    'except_routes' => [
    ],

    /**
     * reauthorize these urls before access, within given timeout
     * you are allowed to use wildcards pattern for url matching
     **/
    'reauthorize_urls' => [
    ],

    /* timeout in minutes */
    'reauthorize_timeout' => 5,

];
