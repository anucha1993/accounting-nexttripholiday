<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class QueryLogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // if (config('app.debug')) {
        //     DB::listen(function($query) {
        //         $sqlWithBindings = vsprintf(str_replace(['%', '?'], ['%%', '%s'], $query->sql), array_map(
        //             function ($binding) {
        //                 return is_numeric($binding) ? $binding : "'{$binding}'";
        //             }, $query->bindings
        //         ));

        //         Log::channel('query')->info(sprintf(
        //             '[%s ms] %s',
        //             $query->time,
        //             $sqlWithBindings
        //         ));
        //     });
        // }
    }
}
