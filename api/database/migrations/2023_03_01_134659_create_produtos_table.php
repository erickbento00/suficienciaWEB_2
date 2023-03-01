<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string("nome", 50);
            $table->decimal("preco");
            $table->timestamps();
        });

        // Insert dos dados fixos para o metodo
        DB::table('produtos')->insert(
            array(
                ['nome' => 'X-Salada', 'preco' => 30.00],
                ['nome' => 'X-Bacon', 'preco' => 35.00],
                ['nome' => 'X-Burger', 'preco' => 20.00],
                ['nome' => 'X-Tudo', 'preco' => 45.00],
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
