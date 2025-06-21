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
        Schema::create('chambres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pavillon')->constrained('locals')->onDelete('cascade');
            $table->string('nom');
            $table->string('numero');
            $table->decimal('superficie', 8, 2)->nullable();
            $table->integer('capacite')->default(5);
            $table->enum('statut', ['libre', 'occupe', 'en maintenance'])->nullable();
            $table->timestamps();

            $table->unique(['id_pavillon', 'numero']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chambres');
    }
};
