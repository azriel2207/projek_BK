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

        if (Str::contains($role, 'siswa')) {
            $candidates = [
                'user_id'      => $user->id,
                'nama_lengkap' => $user->name,
                'nis'          => null,
                'tgl_lahir'    => null,
                'alamat'       => null,
                'no_hp'        => $this->pickPhone($user),
                'kelas'        => null,
            ];

            $payload = $this->buildPayloadForModel($candidates, Student::class);
            if (!empty($payload)) Student::firstOrCreate(['user_id' => $user->id], $payload);
        }

        if (Str::contains($role, 'guru') || Str::contains($role, 'counselor')) {
            $nip = $user->nip ?? $this->generateDefaultNip($user);

            $candidates = [
                'user_id'        => $user->id,
                'nama_lengkap'   => $user->name,
                'nip'            => $nip,
                'email'          => $user->email,
                'no_hp'          => $this->pickPhone($user),
                'specialization' => null,
                'office_hours'   => null,
            ];

            $payload = $this->buildPayloadForModel($candidates, Counselor::class);
            if (!empty($payload)) Counselor::firstOrCreate(['user_id' => $user->id], $payload);
        }
    }
}