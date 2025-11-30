<?php

namespace App\Http\Middleware; // Pastikan namespace-nya app\Http\Middleware

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
// use App\Providers\RouteServiceProvider; // Tidak perlu di Laravel 11 jika tidak ada

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect('login'); 
        }

        if ($request->user()->role !== $role) {
            $userRole = $request->user()->role;
            $redirectPath = match ($userRole) {
                'admin' => '/admin/dashboard',
                'manager' => '/manager/dashboard',
                'staff' => '/staff/dashboard',
                'supplier' => '/supplier/dashboard',
                default => '/dashboard',
            };
            
            return redirect($redirectPath); 
        }

        return $next($request);
    }
}