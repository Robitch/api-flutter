<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Character;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;


class MessageController extends Controller
{




    public function index($id)
    {
        $conversation = Conversation::find($id);

        if (!$conversation) {
            return response()->json(['message' => 'Conversation not found'], 200);
        }

        $messages = Message::where('conversation_id', $conversation->id)->get();

        // Convert is_sent_by_human to boolean
        foreach ($messages as $message) {
            $message->is_sent_by_human = (bool) $message->is_sent_by_human;
        }

        return response()->json($messages);
    }





    public function show($id, $idMessage)
    {
        try {
            $conversation = Conversation::find($id);
            $message = Message::find($idMessage);
            if (!$conversation) {
                throw new ModelNotFoundException('Conversation not found');
            }

            if (!$message) {
                throw new ModelNotFoundException('Message not found in this conversation');
            }

            return response()->json($message);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message' => $exception->getMessage()], 200);
        }
    }


    public function store(Request $request, $id)
    {
        $conversation = Conversation::find($id);

        if (!$conversation) {
            return response()->json(['message' => 'Conversation not found'], 200);
        }

        $character = Character::find($conversation->character_id);

        $validatedData = $request->validate([
            'content' => 'required',
        ]);

        $message = new Message;
        $message->content = $validatedData['content'];
        $message->is_sent_by_human = true;
        $message->conversation_id = $conversation->id;
        $message->save();

        try {
            $client = new Client();
            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    "messages" => [
                        [
                            "role" => "user",
                            "content" => $character->description . "\n\nDans le cadre d'un jeu de rôle, l'IA devient le personnage de " . $character->name . ", et répond à l'humain.\n\nHuman: " . $message->content . "\nAI:",
                        ],
                    ],
                    "model" => "gpt-3.5-turbo",
                    // "max_tokens" => 50,
                    "stop" => ["Human:", "AI:"],
                ],
            ]);

            $responseContent = json_decode($response->getBody(), true);
            $answerContent = $responseContent['choices'][0]['message']['content'];

            $answer = new Message;
            $answer->content = $answerContent;
            $answer->is_sent_by_human = false;
            $answer->conversation_id = $conversation->id;
            $answer->save();

            return response()->json([
                'message' => $message,
                'answer' => $answer,
            ]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return response()->json(['message' => 'Error occurred while processing the message'], 500);
        }
    }
}
