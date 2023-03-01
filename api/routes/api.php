<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("/registro", [App\Http\Controllers\RegistroController::class, "registrar"])->middleware("valida.token");

Route::get('/naoAutenticado', function() {
    return response()->json(["error" => "Não possui credenciais válidas para o recurso solicitado"], 401);
})->name("naoAutenticado");

Route::middleware("auth:sanctum")->group(function() {
    Route::apiResource("comandas", "\App\Http\Controllers\ComandaController");
});