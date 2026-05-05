<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::composer('*', function ($view) {
            if (auth()->check()) {
                $notifications = Notification::where('user_id', auth()->id())
                    ->orderByDesc('created_at')
                    ->get();

                $unreadCount = $notifications->where('is_read', false)->count();

                $view->with(compact('notifications', 'unreadCount'));
            }
        });
    }
}
