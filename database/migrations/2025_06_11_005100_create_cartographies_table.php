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
        Schema::create('cartographies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_site')->constrained('sites')->onDelete('cascade');
            $table->foreignId('id_local')->nullable()->constrained('locals')->onDelete('cascade');
            $table->enum('type', ['Local', 'Couloir', 'Escalier', 'Ascenseur', 'Sortie', 'Autre']);
            $table->decimal('coordonnees_x', 8, 2);
            $table->decimal('coordonnees_y', 8, 2);
            $table->decimal('largeur', 8, 2)->default(50);
            $table->decimal('hauteur', 8, 2)->default(50);
            $table->decimal('rotation', 5, 2)->default(0);
            $table->string('couleur', 7)->default('#CCCCCC');
            $table->string('label')->nullable();
            $table->json('details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cartographies');
    }
};
