<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    // ✅ SIN CONSTRUCTOR - Los middlewares van en las rutas

    public function showLoginForm()
    {
        $autoEmail = session('auto_email', '');
        return view('auth.login', compact('autoEmail'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            if (method_exists($user, 'registrarAcceso')) {
                $user->registrarAcceso();
            }
            
            return redirect()->intended('/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    public function autoLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && $user->estado === 'activo') {
            Auth::login($user);
            
            if (method_exists($user, 'registrarAcceso')) {
                $user->registrarAcceso();
            }
            
            return response()->json([
                'success' => true,
                'message' => '✅ Login exitoso',
                'redirect' => route('dashboard')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => '❌ Usuario no disponible o inactivo'
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}