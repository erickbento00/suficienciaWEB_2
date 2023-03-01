<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class RegistroController extends Controller {
    private $usuario;

    public function __construct(User $usuario) {
        $this->usuario = $usuario;
    }  

    /**
     * @OA\Post(
     *      path="/registro",
     *      tags={"TOKEN"},
     *      summary="Gera um token de acesso",
     *      description="Gera um token de acesso",
     *      @OA\RequestBody(
     *          required = true,
     *          description = "",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  example={
     *                      "nome":"erick",
     *                      "password": "FURB"
     *                  },
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      ),
     *      @OA\PathItem (
     *      ),
     * )
     */
    public function registrar(Request $request) {
        $fields = $request->validate($this->usuario->rules(), $this->usuario->feedback());

        $user = User::create([
            "name" => $fields["name"],
        ]);

        $token = $user->createToken("api_token")->plainTextToken;

        $response = [
            "user" => $user->name,
            "token" => $token
        ];

        return response($response, 201);
    }
}