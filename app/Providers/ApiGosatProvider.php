<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class ApiGosatProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            'api-gosat-v1',
            function () {
                return Http::withOptions([
                    'verify' => false,
                    'base_uri' => 'https://dev.gosat.org/api/v1/'
                ]);
            }
        );
    }
}
