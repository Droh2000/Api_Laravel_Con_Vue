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
        Schema::create('post_tag', function (Blueprint $table) {
            $table->id();
            // Esta es para la columna de FK (Con este metodo los valores siempre seran positivos)
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('tag_id');

            // Creamos el FK
            // En "references" colocamos la referencia al ID de la tabla POST ('on') y al ultimo es la relacion en casacada
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_tag');
    }
};
