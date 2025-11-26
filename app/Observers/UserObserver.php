<?php

namespace App\Observers;

use App\Models\User;
use App\Services\ProfileSyncService;

class UserObserver
{
    protected ProfileSyncService $sync;

    public function __construct()
    {
        $this->sync = app(ProfileSyncService::class);
    }

    public function created(User $user)
    {
        $this->sync->sync($user);
    }

    public function updated(User $user)
    {
        if ($user->wasChanged('role')) {
            $this->sync->sync($user);
        }
    }
}