<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        Paginator::useBootstrapFive();

        Blade::directive('datediff', fn($expression) =>
            "<?= \Carbon\Carbon::parse($expression)->diffForHumans(['aUnit' => true, 'short' => true, 'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]) ?>"
        );

        Blade::directive('date', fn($expression) =>
            "<?= \Carbon\Carbon::parse($expression)->isoFormat('MMM D Y, H:mm') ?>"
        );

        Collection::macro('paginate', function ($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });

        if (env('FORCE_HTTPS', false)) {
            error_log('configuring https');
            $app_url = config("app.url");
            URL::forceRootUrl($app_url);
            $schema = explode(':', $app_url)[0];
            URL::forceScheme($schema);
        }
    }
}