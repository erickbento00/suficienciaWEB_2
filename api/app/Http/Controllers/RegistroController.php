<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class RegistroController extends Controller {
    private $usuario;

    public function __construct(User $usuario) {
        $this->usuario = $usuario;
    }  

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