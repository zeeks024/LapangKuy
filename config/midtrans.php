<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials and configuration for Midtrans payment gateway.
    |
    */

    'merchant_id' => env('MIDTRANS_MERCHANT_ID', 'G929295791'),
    'client_key' => env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-CToATlDoSbNymG4g'),
    'server_key' => env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-aFibID568dSWGZN2KXcwpFwA'),
    
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
    
    'snap_url' => env('MIDTRANS_IS_PRODUCTION', false) ? 
                    'https://app.midtrans.com/snap/v1/transactions' : 
                    'https://app.sandbox.midtrans.com/snap/v1/transactions',
                    
    'status_url' => env('MIDTRANS_IS_PRODUCTION', false) ? 
                    'https://api.midtrans.com/v2/' : 
                    'https://api.sandbox.midtrans.com/v2/',
];
