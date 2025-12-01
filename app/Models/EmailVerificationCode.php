<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EmailVerificationCode extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'expires_at',
        'is_verified',
        'attempts',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'is_verified' => 'boolean',
        ];
    }

    /**
     * Relationship to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate unique 6-digit code
     */
    public static function generateCode(): string
    {
        do {
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (self::where('code', $code)->where('expires_at', '>', now())->exists());

        return $code;
    }

    /**
     * Check if code is still valid (not expired)
     */
    public function isExpired(): bool
    {
        return $this->expires_at < now();
    }

    /**
     * Check if code is valid and not expired
     */
    public function isValid(): bool
    {
        return !$this->isExpired() && !$this->is_verified && $this->attempts < 5;
    }

    /**
     * Increment attempts
     */
    public function incrementAttempts(): void
    {
        $this->increment('attempts');
    }

    /**
     * Mark as verified
     */
    public function markAsVerified(): void
    {
        $this->update(['is_verified' => true]);
    }

    /**
     * Get or create verification code for user
     */
    public static function getOrCreateForUser(User $user, $expiresInMinutes = 15)
    {
        // Delete expired codes
        self::where('user_id', $user->id)
            ->where('expires_at', '<', now())
            ->delete();

        // Check if there's an unverified, non-expired code
        $existing = self::where('user_id', $user->id)
            ->where('is_verified', false)
            ->where('expires_at', '>', now())
            ->first();

        if ($existing) {
            return $existing;
        }

        // Create new code
        return self::create([
            'user_id' => $user->id,
            'code' => self::generateCode(),
            'expires_at' => now()->addMinutes($expiresInMinutes),
        ]);
    }
}
