<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('juegos', function (Blueprint $table) {
            $table->id();
            $table->string('combinacion_real',4)->default(0);
            $table->string('combinacion_actual',4)->default(0);
            $table->integer('intento')->default(0);
            $table->string('combinaciones',1000)->default('NULL');
            $table->string('usuario',50)->default('NULL');
            $table->integer('edad')->default(0);
            $table->integer('rank')->default(0);
            //$table->integer('edad')->default(0);
            $table->timestamps();
            $table->integer('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('juegos');
    }
};
