<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\Counselor;
use App\Observers\UserObserver;
use App\Observers\CounselorObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // register observer supaya User create/update otomatis sinkron ke student/counselor
        User::observe(UserObserver::class);
        
        // register observer supaya Counselor update otomatis sinkron ke User
        Counselor::observe(CounselorObserver::class);
    }
}
