<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model {
    use HasFactory;

    protected $fillable = [
        "nome",
        "telefone"
    ];

    public function rules() {
        return [
            "nome" => "required|string",
            "telefone" => "required|string",
        ];
    }

    public function feedback() {
        return [
            "required" => "O campo :attribute é obrigatório",
            "string" => "O campo :attribute é do tipo string",
        ];
    }
}
