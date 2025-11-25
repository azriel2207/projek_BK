<?php
// app/Http/Controllers/Koordinator/PengaturanController.php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengaturanController extends Controller
{
    public function __construct()
    {
        // Initialize default settings
        SystemSetting::initializeDefaults();
    }

    public function index()
    {
        return view('koordinator.pengaturan.index');
    }

    /**
     * PENGATURAN UMUM - SEMUA DALAM SATU FORM
     */
    public function general()
    {
        // Get all settings needed for the general page
        $schoolName = SystemSetting::getValue('school_name', 'SMAN 1 Contoh Kota');
        $schoolAddress = SystemSetting::getValue('school_address', 'Jl. Contoh No. 123, Kota Contoh');
        $academicYear = SystemSetting::getValue('academic_year', '2023/2024');
        $emailNotification = SystemSetting::getValue('email_notification', true);
        $autoBackup = SystemSetting::getValue('auto_backup', true);
        $backupFrequency = SystemSetting::getValue('backup_frequency', 'weekly');
        $maintenanceMode = SystemSetting::getValue('maintenance_mode', false);

        return view('koordinator.pengaturan.general', compact(
            'schoolName',
            'schoolAddress', 
            'academicYear',
            'emailNotification',
            'autoBackup',
            'backupFrequency',
            'maintenanceMode'
        ));
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'school_name' => 'required|string|max:255',
            'school_address' => 'required|string|max:500',
            'academic_year' => 'required|string|max:50',
            'email_notification' => 'nullable|boolean',
            'auto_backup' => 'nullable|boolean',
            'backup_frequency' => 'required|in:daily,weekly,monthly',
            'maintenance_mode' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            // General Settings
            SystemSetting::setValue('school_name', $request->school_name);
            SystemSetting::setValue('school_address', $request->school_address);
            SystemSetting::setValue('academic_year', $request->academic_year);
            
            // Notification Settings
            SystemSetting::setValue('email_notification', $request->email_notification ? 1 : 0);
            
            // Backup Settings
            SystemSetting::setValue('auto_backup', $request->auto_backup ? 1 : 0);
            SystemSetting::setValue('backup_frequency', $request->backup_frequency);
            
            // System Settings
            SystemSetting::setValue('maintenance_mode', $request->maintenance_mode ? 1 : 0);

            DB::commit();

            return redirect()->route('koordinator.pengaturan.general')
                ->with('success', 'Pengaturan berhasil diperbarui.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui pengaturan: ' . $e->getMessage());
        }
    }

    public function resetSettings()
    {
        try {
            DB::beginTransaction();

            // Reset to default values
            $defaults = [
                'school_name' => 'SMAN 1 Contoh Kota',
                'school_address' => 'Jl. Contoh No. 123, Kota Contoh',
                'academic_year' => '2023/2024',
                'email_notification' => 1,
                'auto_backup' => 1,
                'backup_frequency' => 'weekly',
                'maintenance_mode' => 0,
            ];

            foreach ($defaults as $key => $value) {
                SystemSetting::setValue($key, $value);
            }

            DB::commit();

            return redirect()->route('koordinator.pengaturan.general')
                ->with('success', 'Pengaturan berhasil direset ke nilai default.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mereset pengaturan.');
        }
    }
}