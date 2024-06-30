<?php

namespace App\Providers;

use App\Helpers\ApiResponse;
use App\Helpers\FileExtensionHandler;
use App\Helpers\GenerateUniqueCode;
use App\Helpers\IPCheck;
use App\Helpers\Messaging;
use App\Helpers\Pagination;
use Illuminate\Support\ServiceProvider;

class FacadeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('api.response', function () {
            return new ApiResponse();
        });
    }
}
