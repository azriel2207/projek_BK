public function handle($request, Closure $next, ...$guards)
{
    $this->authenticate($request, $guards);

    // Redirect berdasarkan role setelah login
    $user = $request->user();
    if ($user && !$request->is('dashboard*')) {
        switch($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'counselor':
                return redirect()->route('counselor.dashboard');
            case 'student':
                return redirect()->route('student.dashboard');
        }
    }

    return $next($request);
}