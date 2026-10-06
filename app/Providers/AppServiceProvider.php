<?php

namespace App\Providers;

use App\Models\TblUser;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
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
        Paginator::defaultView('vendor.pagination.custom');
        Paginator::defaultSimpleView('vendor.pagination.custom');

        if (!View::shared('errors')) {
            View::share('errors', new \Illuminate\Support\ViewErrorBag);
        }

        View::composer('*', function ($view) {
            $currentUser = null;
            try {
                if (Schema::hasTable('tbl_user')) {
                    $userId = session('user_id') ?? (Auth::check() ? Auth::id() : null);
                    if ($userId) {
                        $currentUser = TblUser::find($userId);
                    }
                    if (!$currentUser) {
                        $currentUser = TblUser::first();
                    }
                }
            } catch (\Throwable $e) {
                // Ignore DB error during early boot/migrations
            }
            $view->with('currentUser', $currentUser);
        });
    }
}
