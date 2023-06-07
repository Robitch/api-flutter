<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Character;
use App\Models\Univers;
use GuzzleHttp\Client;

class CharacterController extends Controller
{
    public function index($universeId)
    {
        $universe = Univers::findOrFail($universeId);
        $characters = $universe->characters;

        return response()->json($characters);
    }

    public function show($universeId, $characterId)
    {
        $character = Character::where('universe_id', $universeId)->findOrFail($characterId);

        return response()->json($character);
    }

    public function store(Request $request, $universeId)
    {
        $validatedData = $request->validate([
            'name' => 'required',
        ]);

        $universe = Univers::findOrFail($universeId);

        // Vérifie si le personnage existe déjà dans l'univers
        $existingCharacter = $universe->characters()->where('name', $validatedData['name'])->first();

        if ($existingCharacter) {
            return response()->json(['message' => 'Ce personnage existe déjà dans cet univers.'], 409);
        }

        $character = new Character;
        $character->name = $validatedData['name'];
        $character->universe()->associate($universe);
        $character->creator_id = auth()->id();

        $character->save();

        return response()->json($character);
    }

    public function generateDescription($characterId)
    {
        // Récupérer le character à partir de son ID
        $character = Character::findOrFail($characterId);
        $universe = Univers::findOrFail($character->universe_id);

        // echo "Génère une description de ce personnage : {$character->name}; Il provient de l'univers {$universe->name}.";
        // echo "<br><br>";
        // echo env('OPENAI_API_KEY');

        // Appel à l'API OpenAI pour générer la description
        $client = new Client();
        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                // 'prompt' => "Génère une description de ce personnage : {$character->name}; Il provient de l'univers {$universe->name}.",
                // "messages" => [
                //     "content" => "Génère une description de ce personnage : {$character->name}; Il provient de l'univers {$universe->name}.",
                //     "user" => "user",
                // ],
                "messages" => [
                    [
                        "content" => "Voici une conversation entre un humain et une IA spécialisée dans les jeux vidéo. Human: Bonjour, peux-tu me donner une description du personnage {$character->name}. Il provient de l'univers {$universe->name}.\nIA: ",
                        "role" => "user",
                    ],
                ],
                // 'max_tokens' => 50,
                "model" => "gpt-3.5-turbo",
                "stop" => ["Human:", "IA:"],
            ],
        ]);

        // Récupérer la réponse de l'API OpenAI
        $description = json_decode($response->getBody(), true)['choices'][0]['message']['content'];

        // Mettre à jour la description du character dans la base de données
        $character->description = $description;
        $character->save();

        return response()->json($character);
    }
}
