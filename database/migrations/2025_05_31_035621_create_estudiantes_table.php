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
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('estado')->default(1); // Estado del estudiante, por defecto 'activo'
            $table->string('foto', 255)->nullable(); // Foto del estudiante, puede ser nula
            $table->foreignId('especialidade_id')->constrained('especialidades')->onDelete('cascade');
            $table->foreignId('persona_id')->constrained('personas')->onDelete('cascade');
            $table->foreignId('seccione_id')->constrained('secciones')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};
