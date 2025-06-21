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
        Schema::create('chambre_equipement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_chambre')->constrained('chambres')->onDelete('cascade');
            $table->foreignId('id_equipement')->constrained('equipements')->onDelete('cascade');
            $table->integer('quantite')->default(1);
            $table->date('date_installation')->default(now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chambre_equipement');
    }
};
