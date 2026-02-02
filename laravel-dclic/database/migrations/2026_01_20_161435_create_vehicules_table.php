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
        Schema::create('vehicules', function (Blueprint $table) {
            $table->id();
            $table->string('immatriculation')->unique();
            $table->string('marque');
            $table->string('modele');
            $table->string('couleur')->nullable();
            $table->smallInteger('annee')->nullable();
            $table->integer('kilometrage')->nullable();
            $table->string('carrosserie')->nullable();
            $table->string('energie')->nullable();
            $table->string('boite')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicules');
    }
};
