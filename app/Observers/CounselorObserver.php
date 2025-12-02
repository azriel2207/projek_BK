<?php

namespace App\Observers;

use App\Models\Counselor;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CounselorObserver
{
    /**
     * Handle the Counselor "updated" event.
     * Sinkronisasi perubahan di Counselor ke User table
     */
    public function updated(Counselor $counselor)
    {
        try {
            $user = User::find($counselor->user_id);
            
            if ($user) {
                $changedFields = $counselor->getChanges();
                $updateData = [];
                
                // Map Counselor fields ke User fields
                if (isset($changedFields['nama_lengkap'])) {
                    $updateData['name'] = $changedFields['nama_lengkap'];
                }
                
                if (isset($changedFields['no_hp'])) {
                    $updateData['phone'] = $changedFields['no_hp'];
                }
                
                if (isset($changedFields['email'])) {
                    $updateData['email'] = $changedFields['email'];
                }
                
                // Hanya update jika ada perubahan
                if (!empty($updateData)) {
                    // Gunakan raw query untuk menghindari observer loop
                    DB::table('users')
                        ->where('id', $user->id)
                        ->update($updateData);
                    
                    Log::info('User synchronized from Counselor update', [
                        'counselor_id' => $counselor->id,
                        'user_id' => $user->id,
                        'changed_fields' => array_keys($changedFields),
                        'sync_timestamp' => now()
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error syncing Counselor to User', [
                'counselor_id' => $counselor->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
