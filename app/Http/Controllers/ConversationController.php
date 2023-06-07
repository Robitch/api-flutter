<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Character;
use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index()
    {
        $conversations = Conversation::all();
        return response()->json($conversations);
    }

    public function show($id)
    {
        $conversation = Conversation::findOrFail($id);

        // if conversation exists, return it, else return error message
        if ($conversation) {
            return response()->json($conversation);
        } else {
            return response()->json(['message' => 'Conversation not found'], 404);
        }
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'character_id' => 'required|exists:characters,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $characterId = $validatedData['character_id'];
        $userId = $validatedData['user_id'];

        // Vérifier si la conversation existe déjà
        $existingConversation = Conversation::where('character_id', $characterId)->where('user_id', $userId)->first();
        if ($existingConversation) {
            return response()->json(['message' => 'Conversation already exists'], 409);
        }

        // Vérifier si le personnage existe
        $character = Character::find($characterId);
        if (!$character) {
            return response()->json(['message' => 'Character not found'], 404);
        }

        // Vérifier si l'utilisateur existe
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Vérifier si une conversation existe déjà entre le personnage et l'utilisateur
        $conversation = Conversation::firstOrCreate([
            'character_id' => $characterId,
            'user_id' => $userId,
        ]);

        return response()->json($conversation);
    }


    public function destroy($id)
    {
        $conversation = Conversation::findOrFail($id);
        $conversation->delete();

        return response()->json(['message' => 'Conversation deleted']);
    }
}
