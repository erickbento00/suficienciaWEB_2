<?php

namespace App\Http\Controllers;

use App\Models\Comanda;
use App\Models\Produto;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComandaController extends Controller {
    private $comanda;

    public function __construct(Comanda $comanda){
        $this->comanda = $comanda;
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        $comandas = $this->comanda->with('usuario', 'produto')->get();

        if(count($comandas) == 0) {
            return response()->json(["error" => ["text" => "Sem comanda"]], 404);
        }

        // Feito um laço para filtrar os dados dos nós
        foreach($comandas as $comanda) {
            $resposta[] = [
                "idUsuario" => $comanda->idUsuario,
                "nomeUsuario" => $comanda->usuario->nome,
                "telefoneUsuario" => $comanda->usuario->telefone,
            ];
        }

        return response()->json($resposta);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $request->validate($this->comanda->rules(), $this->comanda->feedback());

        $usuario = Usuario::where("id", $request->idUsuario)->where("nome", $request->nomeUsuario)->where("telefone", $request->telefoneUsuario);
        if(!$usuario->exists()){
            $usuario = Usuario::create([
                "nome" => $request->nomeUsuario,
                "telefone" => $request->telefoneUsuario
            ]);
        }else {
            $usuario = $usuario->first();
        }

        $comanda = $this->comanda->create([
            "idUsuario" => $usuario->id
        ]);

        $resposta = [
            "id" => $comanda->id,
            "idUsuario" => $comanda->idUsuario,
            "nomeUsuario" => $usuario->nome,
            "telefoneUsuario" => $usuario->telefone
        ];

        $produtos = Produto::find($request->input("produtos.*.id"));
        foreach($produtos as $produto){
            DB::table("itens_comandas")->insert([
                "produto_id" => $produto->id,
                "comanda_id" => $comanda->id,
                "preco" => $produto->preco,
                "nome" => $produto->nome
            ]);

            $resposta["produtos"][] = [
                "id" => $produto->id,
                "nome" => $produto->nome,
                "preco" => $produto->preco,
            ];
        }
        
        return response()->json($resposta, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id) {
        $comanda = $this->comanda->with('usuario', 'produto')->where("id", $id);
        
        if(!$comanda->exists()){
            return response()->json(["error" => ["text" => "comanda invalida"]], 404);
        }else {
            $comanda = $comanda->first();
        }

        $resposta = [
            "idUsuario" => $comanda->idUsuario,
            "nomeUsuario" => $comanda->usuario->nome,
            "telefoneUsuario" => $comanda->usuario->telefone,
        ];

        foreach($comanda->produto as $produto) {
            $resposta["produtos"][] = [
                "id" => $produto->id,
                "nome" => $produto->pivot->nome,
                "preco" => $produto->pivot->preco,
            ];
        }

        return response()->json($resposta);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {
        if($request->method() === 'PATCH'){
            return response()->json(["error" => "Favor utilizar metodo PUT"], 405);
        }

        foreach($this->comanda->rules() as $input => $regra) {
            if(array_key_exists($input, $request->all())){    
                $regraDinamicas[$input] = $regra;
            }
        }
        
        $regraDinamicas["produtos"] = "required|array";
        $regraDinamicas["produtos.*.id"] = "required|integer";
        $regraDinamicas["produtos.*.nome"] = "required|string";
        $regraDinamicas["produtos.*.preco"] = "required|regex:/^\d+(\.\d{1,2})?$/";
        $request->validate($regraDinamicas, $this->comanda->feedback());

        $itens = DB::table("itens_comandas")->where("comanda_id", $id)->whereIn("id", $request->input("produtos.*.id"));
        if(!$itens->exists()){
            return response()->json(["error" => ["text" => "comanda invalida"]], 404);
        }

        foreach($request->produtos as $produto){
            DB::table("itens_comandas")->where("comanda_id", $id)->where("id", $produto["id"])->update([
                "nome" => $produto["nome"],
                "preco" => $produto["preco"]
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        $comanda = $this->comanda->with('usuario', 'produto')->where("id", $id);
        if(!$comanda->exists()){
            return response()->json(["error" => ["text" => "comanda invalida"]], 404);
        }

        $comanda->delete();
        return response()->json(["success" => ["text" => "comanda removida"]]);
    }
}
