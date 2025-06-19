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
            $table->foreignId('id_reclamation')->constrained('reclamations')->onDelete('cascade');
            $table->foreignId('id_technicien')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->text('description');
            $table->enum('priorite', ['faible', 'normal', 'eleve', 'urgente'])->default('normal');
            $table->date('date_signalement');
            $table->date('date_debut')->nullable();
            $table->date('date_fin_prevue')->nullable();
            $table->date('date_fin_reelle')->nullable();
            $table->enum('statut', ['programme', 'en_cours', 'termine', 'annule', 'en_attente'])->default('en_attente');
            $table->text('remarques')->nullable();
            $table->text('rapport_final')->nullable();
            $table->json('materiel_utilise')->nullable();
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
