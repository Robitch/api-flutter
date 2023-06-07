<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Univers;


class UniverseController extends Controller
{
    public function index()
    {
        // Récupère tous les univers
        $universes = Univers::all();

        // Retourne une réponse JSON avec les univers
        return response()->json($universes);
    }

    public function show($id)
    {
        // Récupère un univers par son ID
        $universe = Univers::find($id);

        // Vérifie si l'univers existe
        if (!$universe) {
            return response()->json(['error' => 'Univers not found'], 404);
        }

        // Retourne une réponse JSON avec l'univers trouvé
        return response()->json($universe);
    }

    public function store(Request $request)
    {
        // Valide les données envoyées dans la requête
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        // Récupère l'id de l'utilisateur connecté
        $creatorId = auth()->id();

        // Vérifie si un univers avec le même nom existe déjà
        $existingUniverse = Univers::where('name', $validatedData['name'])->first();
        if ($existingUniverse) {
            return response()->json(['error' => 'Cet univers existe déjà'], 400);
        }

        // Crée un nouvel univers avec le nom et l'id du créateur
        $universe = new Univers;
        $universe->name = $validatedData['name'];
        $universe->creator_id = $creatorId;

        // Enregistre l'univers dans la base de données
        $universe->save();

        // Retourne une réponse JSON avec l'univers créé
        return response()->json($universe);
    }



    public function update(Request $request, $id)
    {
        // Valide les données envoyées dans la requête
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        // Récupère l'univers par son ID
        $universe = Univers::find($id);

        // Vérifie si l'univers existe

        // Vérifie si un univers avec le même nom existe déjà
        $existingUniverse = Univers::where('name', $validatedData['name'])->first();
        if ($existingUniverse) {
            return response()->json(['error' => 'Cet univers existe déjà'], 400);
        }
        if (!$universe) {
            return response()->json(['error' => 'Univers not found'], 404);
        }

        // Met à jour les champs de l'univers avec les nouvelles valeurs
        $universe->name = $validatedData['name'];

        // Enregistre les modifications de l'univers dans la base de données
        $universe->save();

        // Retourne une réponse JSON avec l'univers mis à jour
        return response()->json($universe);
    }
}
