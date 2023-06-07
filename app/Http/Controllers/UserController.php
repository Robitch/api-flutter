<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function index()
    {
        // Récupère tous les utilisateurs
        $users = User::all();

        // Retourne les utilisateurs en tant que réponse JSON
        return response()->json($users);
    }

    public function show($id)
    {
        // Récupère l'utilisateur avec l'ID spécifié
        $user = User::find($id);

        // Retourne l'utilisateur en tant que réponse JSON
        return response()->json($user);
    }

    public function store(Request $request)
    {
        // Valide les données envoyées dans la requête
        $validatedData = $request->validate([
            'username' => 'required',
            'password' => 'required',
            'email' => 'required|email',
            'firstname' => 'required',
            'lastname' => 'required',
        ]);

        // Crée un nouvel utilisateur avec les données validées
        $user = new User;
        $user->username = $validatedData['username'];
        $user->password = bcrypt($validatedData['password']);
        $user->email = $validatedData['email'];
        $user->firstname = $validatedData['firstname'];
        $user->lastname = $validatedData['lastname'];

        // Enregistre l'utilisateur dans la base de données
        $user->save();

        // Retourne une réponse JSON avec l'utilisateur créé
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        // Récupère l'utilisateur avec l'ID spécifié
        $user = User::find($id);

        // Met à jour les attributs de l'utilisateur avec les données du formulaire
        $user->username = $request->input('username');
        $user->password = bcrypt($request->input('password'));
        $user->email = $request->input('email');
        $user->firstname = $request->input('firstname');
        $user->lastname = $request->input('lastname');

        // Met à jour d'autres attributs si nécessaire

        // Enregistre les modifications de l'utilisateur dans la base de données
        $user->save();

        // Retourne une réponse JSON avec l'utilisateur mis à jour
        return response()->json($user);
    }


    public function login(Request $request)
    {
        // Valide les données envoyées dans la requête
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        // Vérifie si la validation a échoué
        if ($validator->fails()) {
            return response()->json(['error' => 'Validation failed'], 422);
        }

        // Tente d'authentifier l'utilisateur
        if (!Auth::attempt($request->only('username', 'password'))) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Génère le token d'authentification pour l'utilisateur
        $accessToken = Auth::user()->createToken('authToken')->plainTextToken;

        // Retourne le token d'authentification
        return response()->json(['token' => $accessToken, 'id' => Auth::user()->id]);
    }
}
