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
        Schema::create('locals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_sites')->constrained('sites')->onDelete('cascade');
            $table->string('nom');
            $table->decimal('superficie');
            $table->integer('capacite')->default(1);
            $table->boolean('disponible')->default(true);
            $table->enum('statutConforme', ['conforme', 'non conforme', 'en attente'])->default('en attente');
            $table->enum('type', ['cantine', 'espace', 'pavillon']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locals');
    }
};
