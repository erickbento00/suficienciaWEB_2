<?php

namespace App\Http\Controllers;

use App\Models\Comanda;
use App\Models\Produto;
use App\Models\Usuario;
use Illuminate\Http\Request;

class ComandaController extends Controller {
    private $usuario;
    private $produto;

    public function __construct(Usuario $usuario, Produto $produto){
        $this->usuario = $usuario;
        $this->produto = $produto;
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Comanda $comanda) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comanda $comanda) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comanda $comanda) {
        //
    }
}
