<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Counselor;
use App\Models\CounselingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect('/')->with('error', 'Silakan login terlebih dahulu.');
        }

        switch($user->role) {
            case 'admin':
                return $this->adminDashboard();
            case 'counselor':
                return $this->counselorDashboard();
            case 'student':
                return $this->studentDashboard();
            default:
                Auth::logout();
                return redirect('/')->with('error', 'Role tidak valid.');
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
        $counselor = Auth::user()->counselor;
        
        if (!$counselor) {
            Auth::logout();
            return redirect('/')->with('error', 'Data konselor tidak ditemukan.');
        }

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
        $student = Auth::user()->student;
        
        if (!$student) {
            Auth::logout();
            return redirect('/')->with('error', 'Data siswa tidak ditemukan.');
        }

        $stats = [
            'upcomingSessions' => CounselingSession::where('student_id', $student->id)
                ->where('status', 'dijadwalkan')
                ->count(),
        ];

        return view('dashboard.student', compact('stats'));
    }
}