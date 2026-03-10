<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ── Inscription ───────────────────────────────────────────
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'nom'            => ['required', 'string', 'max:100'],
            'prenom'         => ['required', 'string', 'max:100'],
            'email'          => ['required', 'email', 'unique:users,email'],
            'telephone'      => ['required', 'string', 'max:20'],
            'date_naissance' => ['required', 'date', 'before:-18 years'],
            'password'       => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $client = Client::create([
            ...$data,
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($client);

        return redirect()->route('client.dashboard')
            ->with('success', "Bienvenue " . $client->prenom . " !");
    }

    // ── Connexion ─────────────────────────────────────────────
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            return match($user->type) {
                'admin'  => redirect()->route('admin.dashboard'),
                'agent'  => redirect()->route('agent.dashboard'),
                default  => redirect()->route('client.dashboard'),
            };
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => 'Email ou mot de passe incorrect.']);
    }

    // ── Déconnexion ───────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
