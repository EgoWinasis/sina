<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckProfile
{
    public function handle($request, Closure $next)
    {
        $user =  Auth::user();
        
        if ($user->kantor != '-' && $user->jabatan != '-'  && $user->image != 'user_default.png') {
            return $next($request); // User has access to "pengajuan cuti" menu
        }
        
        // Redirect or handle unauthorized access here
        return redirect()->route('profile.index')->with('error', 'Unauthorized access. Update profile first');
    }
}
