<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UniverseController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\MessageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('jwt.auth')->group(function () {
    // Routes protégées nécessitant une authentification JWT

    Route::get('/universes', [UniverseController::class, 'index']);
    Route::get('/universes/{id}', [UniverseController::class, 'show']);
    Route::post('/universes', [UniverseController::class, 'store']);
    Route::put('/universes/{id}', [UniverseController::class, 'update']);

    Route::get('/universes/{universeId}/characters', [CharacterController::class, 'index']);
    Route::get('/universes/{universeId}/characters/{characterId}', [CharacterController::class, 'show']);
    Route::post('/universes/{universeId}/characters', [CharacterController::class, 'store']);
    Route::put('/universes/{universeId}/characters/{characterId}', [CharacterController::class, 'generateDescription']);

    // Get all conversations, get one conversation, create a conversation, delete a conversation
    Route::get('/conversations', [ConversationController::class, 'index']);
    Route::get('/conversations/{id}', [ConversationController::class, 'show']);
    Route::post('/conversations', [ConversationController::class, 'store']);
    Route::delete('/conversations/{id}', [ConversationController::class, 'destroy']);

    Route::get('/conversations/{id}/messages', [MessageController::class, 'index']);
    Route::get('/conversations/{id}/messages/{idMessage}', [MessageController::class, 'show']);
    Route::post('/conversations/{id}/messages', [MessageController::class, 'store']);
});

// Autres routes publiques

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
