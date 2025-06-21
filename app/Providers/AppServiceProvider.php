<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register str_limit helper for string truncation
        \Illuminate\Support\Str::macro('limit', function ($string, $limit = 100, $end = '...') {
            return mb_strlen($string) > $limit ? mb_substr($string, 0, $limit) . $end : $string;
        });
        
        // Always load Midtrans configuration directly from .env to avoid any caching issues
        if (env('MIDTRANS_SERVER_KEY') && env('MIDTRANS_CLIENT_KEY')) {
            // Get the keys from environment
            $serverKey = env('MIDTRANS_SERVER_KEY');
            $clientKey = env('MIDTRANS_CLIENT_KEY');
            $merchantId = env('MIDTRANS_MERCHANT_ID');
            
            // Set the configuration
            Config::set('midtrans.merchant_id', $merchantId);
            Config::set('midtrans.client_key', $clientKey);
            Config::set('midtrans.server_key', $serverKey);
            
            // Force the environment to match the key type
            // Keys starting with "SB-" are for sandbox
            $isProduction = (strpos($serverKey, 'SB-') !== 0);
            Config::set('midtrans.is_production', $isProduction);
            
            // Log the configuration
            \Log::info('Midtrans configuration loaded', [
                'merchant_id' => $merchantId,
                'environment' => $isProduction ? 'PRODUCTION' : 'SANDBOX',
                'client_key_prefix' => substr($clientKey, 0, 6) . '...',
                'server_key_prefix' => substr($serverKey, 0, 6) . '...',
            ]);
        } 
        // Set default testing configuration if keys aren't configured properly
        else {
            // Use dummy sandbox keys for testing without real Midtrans integration
            Config::set('midtrans.is_production', false);
            Config::set('midtrans.merchant_id', 'G123456789');
            Config::set('midtrans.client_key', 'SB-Mid-client-xxxxxxxxxxxxxxxx');
            Config::set('midtrans.server_key', 'SB-Mid-server-xxxxxxxxxxxxxxxx');
            
            \Log::info('Using test mode for Midtrans (fake tokens)');
        }
    }
}
