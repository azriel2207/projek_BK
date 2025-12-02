<?php

namespace App\Services;

class StudentService
{
    /**
     * Generate unique NIS for a student
     * Format: YYYY{counter}
     * Example: 2025001, 2025002, etc.
     */
    public static function generateNIS()
    {
        $year = date('Y');
        
        // Get max NIS for this year
        $lastStudents = \App\Models\Student::where('nis', 'LIKE', $year . '%')
            ->orderBy('nis', 'desc')
            ->limit(1)
            ->first();

        if ($lastStudents) {
            // Extract counter from last NIS
            $lastNis = $lastStudents->nis;
            $lastCounter = (int) substr($lastNis, 4);
            $newCounter = $lastCounter + 1;
        } else {
            // First student for this year
            $newCounter = 1;
        }

        return $year . str_pad($newCounter, 3, '0', STR_PAD_LEFT);
    }
}
