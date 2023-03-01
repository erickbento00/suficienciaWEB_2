<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up():void {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("usuario_id");
            $table->string("nome", 100);
            $table->decimal("valor", 8, 2);
            $table->timestamps();

            // Foreign key
            $table->foreign("usuario_id")->references("id")->on("usuarios");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down():void {
        Schema::dropIfExists('produtos');
    }
};
