<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        foreach (glob(app_path('Helpers/*.php')) as $file) {
            require_once($file);
        }
        foreach (glob(app_path('CustomHelpers/*.php')) as $file) {
            require_once($file);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        \Illuminate\Support\Facades\Blade::directive('bathText', function ($expression) {
            return "<?php echo App\Helpers\BathTextHelper::convert($expression); ?>";
        });
    }
}