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
        Schema::create('equipements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_local')->constrained('locals')->onDelete('cascade');
            $table->string('nom');
            $table->enum('type',  ['mobilier', 'electromenager', 'informatique', 'chauffage', 'plomberie', 'electricite', 'autre']);
            $table->enum('etat', ['neuf', 'bon', 'use', 'hors service']);
            $table->date('dateDebut');
            $table->date('dateFin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipements');
    }
};
