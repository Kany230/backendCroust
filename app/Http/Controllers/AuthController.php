<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Auth;
use \App\Models\User;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        // Valider les données
        $data = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:etudiant,commerçant',
        ]);

        // Créer l'utilisateur
        $user = User::create([
            'firstname' => $data['name'],
            'lastname' => $data['lastname'],
            'role' => $data['role'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        // Auth::login($user);

        return response()->json(['message' => 'Inscription reussie', '$data' => $user], 201);
    }

    //Completer les données de l'utilisateur etudiant
    public function completeRegistration(Request $request)
    {
        // Validate the request data
        $data = $request->validate([
            'sexe' => 'required|string',
            'age' => 'required|integer',
            'adresse' => 'required|string',
            'telephone' => 'required|string',
            'niveauEtude' => 'required|string',
            'numDossier' => 'required|string',
            'filiere' => 'required|string',
            'domaine' => 'required|string',
            'numCIN' => 'required|string',
            'photo' => 'nullable|image|max:2048', // Optional photo upload
        ]);

        // lutiliser Auth pour obtenir l'utilisateur connecté
        $user = User::find(Auth::id());



        $user->update($data);

        return response()->json(['message' => 'Informations mises à jour avec succès', '$data' => $user], 200);
    }

    // Le profil de l'utilisateur
    public function profile(Request $request)
    {
        // lutiliser Auth pour obtenir l'utilisateur connecté
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        return response()->json($user, 200);
    }

    public function login(Request $request)
    {
        // Validate the request data
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return response()->json(['message' => 'Connexion reussie'], 200);
        }

        return response()->json(['message' => 'Email ou mot de passe incoorect'], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Deconnexion reussie'], 200);
    }
}
