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
        Schema::create('controle_q_h_s_e_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_reservation')->constrained('reservations')->onDelete('cascade');
            $table->foreignId('id_local')->constrained('locals')->onDelete('cascade');
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->date('dateDebut');
            $table->date('dateFin');
            $table->text('conclusion');
            $table->boolean('conforme')->default(false);
            $table->enum('type', ['en cours', 'termine', 'en attente'])->default('en attente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('controle_q_h_s_e_s');
    }
};
