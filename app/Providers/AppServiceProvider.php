<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\WorkCalendar;
use App\Observers\WorkCalendarObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Aktifkan gaya pagination Bootstrap 5
        Paginator::useBootstrapFive();
        
        // 🔄 Register Observer untuk auto-sync Calendar & Attendance
        WorkCalendar::observe(WorkCalendarObserver::class);
    }
}
