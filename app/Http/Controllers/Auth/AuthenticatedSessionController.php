<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
        // 🔐 Authenticate user
        $request->authenticate();

        // 🔁 Regenerate session (anti session fixation)
        $request->session()->regenerate();

        // 👤 Get authenticated user
        $user = Auth::user();

        // 🔀 Role-based redirect (case-safe)
        return match ($user->role) {
            'dosen' => redirect()->route('dosen.dashboard'),
            'mahasiswa' => redirect()->route('mahasiswa.beranda'),
            default => redirect('/'),
        };
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // 🚪 Logout user
        Auth::guard('web')->logout();

        // 🧹 Clear session
        $request->session()->invalidate();

        // 🔁 Regenerate CSRF token (PENTING untuk hindari 419)
        $request->session()->regenerateToken();

        // 🔁 Redirect ke login
        return redirect()->route('login');
    }
}