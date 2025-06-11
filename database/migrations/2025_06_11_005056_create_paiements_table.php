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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_local')->constrained('locals')->onDelete('cascade');
            $table->decimal('montant', 10, 2);
            $table->date('dateDebut');
            $table->date('dateEcheance');
            $table->enum('method_paiement', ['wave', 'OM', 'espece']);
            $table->enum('statut', ['en attente', 'validee', 'en retard', 'annule']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
