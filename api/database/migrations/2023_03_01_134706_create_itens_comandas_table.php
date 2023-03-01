<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('itens_comandas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("produto_id");
            $table->unsignedBigInteger("comanda_id");
            $table->string("nome", 50);
            $table->decimal("preco");
            $table->timestamps();

            // Foreign key
            $table->foreign("produto_id")->references("id")->on("produtos");
            $table->foreign("comanda_id")->references("id")->on("comandas")->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itens_comandas');
    }
};
