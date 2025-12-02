<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Counselor;
use App\Services\ProfileSyncService;
use Illuminate\Support\Facades\Log;

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
        
        // Sinkronisasi perubahan di User ke Counselor jika user adalah guru_bk
        if ($user->role === 'guru_bk' || $user->role === 'guru') {
            $this->syncUserToCounselor($user);
        }
    }
    
    /**
     * Sinkronisasi perubahan User ke Counselor record
     */
    protected function syncUserToCounselor(User $user)
    {
        try {
            $counselor = Counselor::where('user_id', $user->id)->first();
            
            if ($counselor) {
                $changedFields = $user->getChanges();
                $updateData = [];
                
                // Map User fields ke Counselor fields
                if (isset($changedFields['name'])) {
                    $updateData['nama_lengkap'] = $changedFields['name'];
                }
                
                if (isset($changedFields['phone'])) {
                    $updateData['no_hp'] = $changedFields['phone'];
                }
                
                if (isset($changedFields['email'])) {
                    $updateData['email'] = $changedFields['email'];
                }
                
                // Hanya update jika ada perubahan
                if (!empty($updateData)) {
                    // Gunakan raw query untuk menghindari observer loop
                    \Illuminate\Support\Facades\DB::table('counselors')
                        ->where('id', $counselor->id)
                        ->update($updateData);
                    
                    Log::info('Counselor synchronized from User update', [
                        'user_id' => $user->id,
                        'counselor_id' => $counselor->id,
                        'changed_fields' => array_keys($changedFields),
                        'sync_timestamp' => now()
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error syncing User to Counselor', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}