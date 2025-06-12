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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_local')->constrained('locals')->onDelete('cascade');
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->date('dateDebut');
            $table->date('dateFin');
            $table->string('description')->nullable();
            $table->string('choixLocal');
            $table->string('produitOuService')->nullable();
            $table->boolean('qualiteQHSE')->nullable();
            $table->string('nombreCredit')->nullable();
            $table->string('moyenneAnnuelle')->nullable();
            $table->enum('statutDemande', ['en attente', 'approuvee', 'rejetee'])->default('en attente');
            $table->string('nomFichier');
            $table->string('cheminFichier');
            $table->string('typeFichier');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
