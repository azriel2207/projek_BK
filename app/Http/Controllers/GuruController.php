<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruController extends Controller
{
    /**
     * Menampilkan dashboard guru BK
     */
    public function dashboard()
    {
        return view('guru.dashboard', [
            'user' => Auth::user()
        ]);
    }
}