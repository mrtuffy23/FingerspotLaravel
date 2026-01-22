<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\WorkCalendar;
use App\Models\Attendance;
use App\Observers\WorkCalendarObserver;
use App\Observers\AttendanceObserver;

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
        
        // 🔄 Register Observer untuk auto-sync Calendar
        WorkCalendar::observe(WorkCalendarObserver::class);
        
        // 🔄 Register Attendance Observer untuk auto-sync Points
        Attendance::observe(AttendanceObserver::class);
    }
}
