<?php

namespace PortedCheese\SiteReviews;

use PortedCheese\BaseSettings\Events\UserUpdate;
use PortedCheese\SiteReviews\Console\Commands\ReviewsMakeCommand;
use PortedCheese\SiteReviews\Listeners\UserUpdateClearCache;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function boot()
    {
        // Подключить миграции.
        $this->loadMigrationsFrom(__DIR__ . "/database/migrations");

        // Подключить роуты.
        $this->loadRoutesFrom(__DIR__ . "/routes/admin.php");

        // Подключаем шаблоны.
        $this->loadViewsFrom(__DIR__ . "/resources/views", "site-reviews");
        view()->composer("site-reviews::admin.menu", function ($view) {
            $view->with('needModerate', siteconf()->get('reviews.needModerate'));
        });

        // Копирование шаблонов.
        $this->publishes([
            __DIR__ . '/resources/views/site' => resource_path('views/vendor/site-reviews/site/reviews'),
        ], 'views');

        // Assets.
        $this->publishes([
            __DIR__ . '/resources/js/components' => resource_path('js/components/vendor/site-reviews'),
        ], 'public');

        // Console.
        if ($this->app->runningInConsole()) {
            $this->commands([
                ReviewsMakeCommand::class,
            ]);
        }

        // Подписаться на обновление пользователя.
        $this->app['events']->listen(UserUpdate::class, UserUpdateClearCache::class);
    }

    public function register()
    {

    }

}
