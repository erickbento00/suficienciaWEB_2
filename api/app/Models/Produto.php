<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model {
    use HasFactory;

    protected $fillable = [
        "nome",
        "preco"
    ];

    public function rules() {
        return [
            "produtos" => "required|array",
            "produtos.*.nome" => "required|string",
            "produtos.*.preco" => "required|regex:/^\d+(\.\d{1,2})?$/",
        ];
    }

    public function feedback() {
        return [
            "required" => "O campo :attribute é obrigatório",
            "integer" => "O campo :attribute é do tipo integer",
            "string" => "O campo :attribute é do tipo string",
            "regex" => "O campo :attribute tem que ser um valor valido em float. Ex.: 12.00"
        ];
    }

    public function comanda(){
        // Muitos para muitos, defino uma tabela intermediaria
        return $this->belongsToMany(Comanda::class, "itens_comandas", "produto_id", "comanda_id")->withPivot(['nome','preco']);
    }
}
