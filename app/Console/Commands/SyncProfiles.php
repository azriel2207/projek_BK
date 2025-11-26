<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\ProfileSyncService;

class SyncProfiles extends Command
{
    protected $signature = 'app:sync-profiles {--chunk=500 : chunk size}';
    protected $description = 'Sinkronkan semua user ke tabel students / counselors berdasarkan role';

    public function handle(ProfileSyncService $service)
    {
        $chunk = (int) $this->option('chunk');

        User::chunk($chunk, function ($users) use ($service, $chunk) {
            foreach ($users as $user) {
                $service->sync($user);
            }
            $this->info("Processed chunk of {$chunk} users.");
        });

        $this->info('Sinkronisasi selesai.');
        return 0;
    }
}