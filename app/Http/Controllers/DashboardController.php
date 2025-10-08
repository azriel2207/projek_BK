<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Counselor;
use App\Models\CounselingSession;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        switch($user->role) {
            case 'admin':
                return $this->adminDashboard();
            case 'counselor':
                return $this->counselorDashboard();
            case 'student':
                return $this->studentDashboard();
        }
    }

    private function adminDashboard()
    {
        $stats = [
            'totalStudents' => Student::count(),
            'totalCounselors' => Counselor::count(),
            'totalSessions' => CounselingSession::count(),
            'pendingSessions' => CounselingSession::where('status', 'menunggu_konfirmasi')->count(),
        ];

        return view('dashboard.admin', compact('stats'));
    }

    private function counselorDashboard()
    {
        $counselor = auth()->user()->counselor;
        $stats = [
            'upcomingSessions' => CounselingSession::where('counselor_id', $counselor->id)
                ->where('status', 'dijadwalkan')
                ->count(),
            'totalStudents' => Student::count(),
        ];

        return view('dashboard.counselor', compact('stats'));
    }

    private function studentDashboard()
    {
        $student = auth()->user()->student;
        $stats = [
            'upcomingSessions' => CounselingSession::where('student_id', $student->id)
                ->where('status', 'dijadwalkan')
                ->count(),
        ];

        return view('dashboard.student', compact('stats'));
    }
}