<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comanda extends Model {
    use HasFactory;

    protected $fillable = [
        "idUsuario"
    ];

    public function rules() {
        return [
            "idUsuario" => "required|integer",
            "nomeUsuario" => "required|string",
            "telefoneUsuario" => "required|string",
            "produtos" => "required|array",
            "produtos.*.id" => "required|integer",
            "produtos.*.nome" => "required|string",
            "produtos.*.preco" => "required|regex:/^\d+(\.\d{1,2})?$/",
        ];
    }

    public function feedback() {
        return [
            "required" => "O campo :attribute é obrigatório",
            "integer" => "O campo :attribute é do tipo integer",
            "string" => "O campo :attribute é do tipo string",
            "regex" => "O campo :attribute tem que ser um valor valido em float. Ex.: 12.00",
            "produtos.*.required" => "O campo :attribute é obrigatório",
            "produtos.*.integer" => "O campo :attribute é do tipo integer",
            "produtos.*.string" => "O campo :attribute é do tipo string",
            "produtos.*.regex" => "O campo :attribute tem que ser um valor valido em float. Ex.: 12.00"
        ];
    }
}
