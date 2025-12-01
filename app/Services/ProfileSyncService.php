<?php

namespace App\Services;

use App\Models\User;
use App\Models\Student;
use App\Models\Counselor;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class ProfileSyncService
{
    protected function pickPhone(User $user)
    {
        return $user->phone ?? $user->no_hp ?? $user->telepon ?? null;
    }

    protected function buildPayloadForModel(array $candidates, $model)
    {
        $table = (new $model)->getTable();
        $payload = [];
        foreach ($candidates as $key => $value) {
            if (Schema::hasColumn($table, $key)) {
                $payload[$key] = $value;
            }
        }
        return $payload;
    }

    protected function generateDefaultNip(User $user): string
    {
        // contoh: NIP000034
        return 'NIP' . str_pad((string) $user->id, 6, '0', STR_PAD_LEFT);
    }

    public function sync(User $user): void
    {
        $role = Str::lower(str_replace([' ', '_'], '', (string) $user->role));

        // Jangan sync untuk siswa karena Student record dibuat manual di storeSiswa
        // dan kita tidak ingin duplikasi
        if (Str::contains($role, 'siswa')) {
            // Jangan buat Student otomatis - biarkan storeSiswa yang atur
            return;
        }

        // Jangan sync untuk guru_bk karena Counselor record dibuat manual di storeGuru
        // dan kita tidak ingin duplikasi
        if (Str::contains($role, 'guru') || Str::contains($role, 'counselor')) {
            // Jangan buat Counselor otomatis - biarkan storeGuru yang atur
            return;
        }
    }
}