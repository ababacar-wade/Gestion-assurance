<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // ── Profil de l'utilisateur connecté ─────────────────────
    public function profil()
    {
        return view('profil', ['user' => Auth::user()]);
    }

    public function updateProfil(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $data = $request->validate([
            'nom'       => ['required', 'string', 'max:100'],
            'prenom'    => ['required', 'string', 'max:100'],
            'telephone' => ['required', 'string', 'max:20'],
            'adresse'   => ['nullable', 'string', 'max:255'],
            'photo'     => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('avatars', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Profil mis à jour.');
    }

    // ── Changement de mot de passe ────────────────────────────
    public function updatePassword(Request $request)
    {
        $request->validate([
            'ancien_password'  => ['required'],
            'password'         => ['required', 'min:8', 'confirmed'],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check($request->ancien_password, $user->password)) {
            return back()->withErrors(['ancien_password' => 'Mot de passe actuel incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Mot de passe modifié.');
    }

    // ── Liste complète (Admin uniquement) ─────────────────────
    public function index()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function destroy(User $user)
    {
        abort_if($user->id === Auth::id(), 403, 'Impossible de se supprimer soi-même.');
        $user->delete();
        return back()->with('success', 'Utilisateur supprimé.');
    }
}
