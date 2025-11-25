<?php
// app/Models/SystemSetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SystemSetting extends Model
{
    protected $fillable = ['key', 'value', 'group', 'type', 'description'];
    
    public $timestamps = true;

    /**
     * Get setting value by key
     */
    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? self::castValue($setting->value, $setting->type) : $default;
    }

    /**
     * Set setting value
     */
    public static function setValue($key, $value)
    {
        $setting = self::where('key', $key)->first();
        
        if ($setting) {
            $setting->update(['value' => $value]);
        } else {
            self::create([
                'key' => $key,
                'value' => $value,
                'group' => 'general',
                'type' => 'string'
            ]);
        }
        
        return true;
    }

    /**
     * Cast value based on type
     */
    private static function castValue($value, $type)
    {
        switch ($type) {
            case 'boolean':
                return (bool) $value;
            case 'integer':
                return (int) $value;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    /**
     * Get all settings by group
     */
    public static function getByGroup($group)
    {
        return self::where('group', $group)->get();
    }

    /**
     * Initialize default settings if table is empty
     */
    public static function initializeDefaults()
    {
        if (self::count() === 0) {
            $settings = [
                // General Settings
                ['key' => 'school_name', 'value' => 'SMAN 1 Contoh Kota', 'group' => 'general', 'type' => 'string', 'description' => 'Nama sekolah'],
                ['key' => 'school_address', 'value' => 'Jl. Contoh No. 123, Kota Contoh', 'group' => 'general', 'type' => 'string', 'description' => 'Alamat sekolah'],
                ['key' => 'academic_year', 'value' => '2023/2024', 'group' => 'general', 'type' => 'string', 'description' => 'Tahun ajaran'],
                
                // Notification Settings
                ['key' => 'email_notification', 'value' => '1', 'group' => 'notification', 'type' => 'boolean', 'description' => 'Notifikasi email'],
                
                // Backup Settings
                ['key' => 'auto_backup', 'value' => '1', 'group' => 'backup', 'type' => 'boolean', 'description' => 'Backup otomatis'],
                ['key' => 'backup_frequency', 'value' => 'weekly', 'group' => 'backup', 'type' => 'string', 'description' => 'Frekuensi backup'],
                
                // System Settings
                ['key' => 'maintenance_mode', 'value' => '0', 'group' => 'system', 'type' => 'boolean', 'description' => 'Maintenance mode'],
            ];

            foreach ($settings as $setting) {
                self::create($setting);
            }
        }
    }
}