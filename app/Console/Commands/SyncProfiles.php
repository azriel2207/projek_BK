<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\ProfileSyncService;

class SyncProfiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:profiles {--role=* : optional role filter (siswa|guru|koordinator)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync existing users to students/counselors by running ProfileSyncService';

    protected ProfileSyncService $syncService;

    public function __construct(ProfileSyncService $syncService)
    {
        parent::__construct();
        $this->syncService = $syncService;
    }

    public function handle()
    {
        $roleFilters = $this->option('role');

        $this->info('Starting profile sync...');

        $query = User::query();
        if (!empty($roleFilters)) {
            $query->whereIn('role', $roleFilters);
        }

        $total = 0;
        $query->chunk(200, function ($users) use (&$total) {
            foreach ($users as $user) {
                try {
                    $this->syncService->sync($user);
                    $total++;
                } catch (\Exception $e) {
                    $this->error("Failed to sync user {$user->id}: {$e->getMessage()}");
                }
            }
        });

        $this->info("Profile sync completed. Processed: {$total} users.");
        return 0;
    }
}
