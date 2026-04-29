<?php

namespace App\Providers;

use App\Models\SiteAdmin;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification;
use Illuminate\Support\Facades\Blade;


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
    public function boot()
    {
        // Share notifications and notification count with all views
        View::composer('*', function ($view) {
            $notifications = Notification::orderBy('is_seen')->latest('created_at')->limit(3)->get();
            $notification_count = Notification::where("is_seen", 0)->count();

            $view->with('notifications', $notifications);
            $view->with('notification_count', $notification_count);
        });

        Blade::componentNamespace('App\\View\\Components\\Frontend', 'frontend');
    }

}
