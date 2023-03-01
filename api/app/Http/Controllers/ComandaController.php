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
    /**
     * @OA\Get(
     *      path="/comandas",
     *      tags={"COMANDAS"},
     *      summary="Retorna as informações da cada comanda, podendo ter varias comandas pro mesmo usuario",
     *      description="Retorna as informações da cada comanda, podendo ter varias comandas pro mesmo usuario",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  example={
     *                      {
     *                          "idUsuario": 1,
     *                          "nomeUsuario": "erick",
     *                          "telefoneUsuario": "4712341234"
     *                      },
     *                      {
     *                          "idUsuario": 1,
     *                          "nomeUsuario": "erick",
     *                          "telefoneUsuario": "4712341234"
     *                      },
     *                      {
     *                          "idUsuario": 2,
     *                          "nomeUsuario": "kcire",
     *                          "telefoneUsuario": "4712341234"
     *                      },
     *                  }
     *              )
     *          ),
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
    /**
     * @OA\Post(
     *      path="/comandas",
     *      tags={"COMANDAS"},
     *      summary="Adiciona novas comandas, usuarios e produtos",
     *      description="Adiciona novas comandas, usuarios e produtos",
     *      @OA\RequestBody(
     *          required = true,
     *          description = "",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  example={
     *                      "idUsuario":1,
     *                      "nomeUsuario":"erick",
     *                      "telefoneUsuario":"4712341234",
     *                      "produtos":{
     *                          {
     *                              "id":1,
     *                              "nome":"X-Salada",
     *                              "preco":30
     *                          },
     *                          {
     *                              "id":2,
     *                              "nome":"X-Bacon",
     *                              "preco":35
     *                          }
     *                      }
     *                  },
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  example={
     *                      "id": 1,
     *                      "idUsuario":1,
     *                      "nomeUsuario":"erick",
     *                      "telefoneUsuario":"4712341234",
     *                      "produtos":{
     *                          {
     *                              "id":1,
     *                              "nome":"X-Salada",
     *                              "preco":30
     *                          },
     *                          {
     *                              "id":2,
     *                              "nome":"X-Bacon",
     *                              "preco":35
     *                          }
     *                      }
     *                  }
     *              )
     *          ),
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
    /**
     * @OA\Get(
     *      path="/comandas/{id}",
     *      tags={"COMANDAS"},
     *      summary="Retorna as informações de uma comanda especifica",
     *      description="As informações da comanda consiste nas informações dos usuarios e seus itens, com nome e valor",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Código da comanda",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  example={
     *                      {
     *                          "idUsuario": 1,
     *                          "nomeUsuario": "erick",
     *                          "telefoneUsuario": "4712341234",
     *                          "produtos": {
     *                              {
     *                                  "id": 1,
     *                                  "nome": "X-Salada",
     *                                  "preco": "20.00"
     *                              },
     *                              {
     *                                  "id": 2,
     *                                  "nome": "X-Bacon",
     *                                  "preco": "15.00"
     *                              }
     *                          }
     *                      }
     *                  }
     *              )
     *          ),
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
    /**
     * @OA\Put(
     *      path="/comandas/{id}",
     *      tags={"COMANDAS"},
     *      summary="Atualiza as informações dos itens da comanda",
     *      description="Atualiza as informações dos itens da comanda",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Código da comanda",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required = true,
     *          description = "",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  example={
     *                      "produtos":{
     *                          {
     *                              "id":1,
     *                              "nome":"X-Salada",
     *                              "preco":20
     *                          },
     *                          {
     *                              "id":2,
     *                              "nome":"X-Bacon",
     *                              "preco":15
     *                          }
     *                      }
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

        $itens = DB::table("itens_comandas")->where("comanda_id", $id)->whereIn("produto_id", $request->input("produtos.*.id"));
        if(!$itens->exists()){
            return response()->json(["error" => ["text" => "comanda invalida"]], 404);
        }

        foreach($request->produtos as $produto){
            DB::table("itens_comandas")->where("comanda_id", $id)->where("produto_id", $produto["id"])->update([
                "nome" => $produto["nome"],
                "preco" => $produto["preco"]
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *      path="/comandas/{id}",
     *      tags={"COMANDAS"},
     *      summary="Exclui as informações da comanda",
     *      description="Exclui as informações da comanda",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Código da comanda",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  example={
     *                      "success": {
     *                          "text": "comanda invalida"
     *                      }
     *                  }
     *              )
     *          ),
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
    public function destroy($id) {
        $comanda = $this->comanda->with('usuario', 'produto')->where("id", $id);
        if(!$comanda->exists()){
            return response()->json(["error" => ["text" => "comanda invalida"]], 404);
        }

        $comanda->delete();
        return response()->json(["success" => ["text" => "comanda removida"]]);
    }
}
