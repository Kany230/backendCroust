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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_local')->constrained('locals')->onDelete('cascade');
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->text('description');
            $table->enum('priorite', ['faible', 'normale', 'eleve', 'urgente'])->default('Normale');
            $table->date('dateSignalement');
            $table->date('dateDebut')->nullable();
            $table->date('dateFin')->nullable();
            $table->enum('statut', ['programme', 'en cours', 'termine', 'annule', 'en attente'])->default('en attente');
            $table->text('remarques')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
