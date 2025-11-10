<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KoordinatorController extends Controller
{
    /**
     * Menampilkan dashboard koordinator BK
     */
    public function dashboard()
    {
        return view('koordinator.dashboard', [
            'user' => Auth::user()
        ]);
    }
}