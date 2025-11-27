<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Route;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        
        // Logika Redireksi Kustom Berdasarkan Peran
        $user = $request->user();
        
        switch ($user->role) {
            case 'admin':
                $redirectPath = '/admin/dashboard';
                break;
            case 'manager':
                $redirectPath = '/manager/dashboard';
                break;
            case 'staff':
                $redirectPath = '/staff/dashboard';
                break;
            case 'supplier':
                // Cek status approval supplier
                if (!$user->is_approved) {
                    $redirectPath = '/supplier/dashboard'; // Kita akan tangani view pending di rute ini
                } else {
                    $redirectPath = '/supplier/dashboard';
                }
                break;
            default:
                $redirectPath = '/dashboard';
                break;
        }

        return redirect()->intended($redirectPath);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
