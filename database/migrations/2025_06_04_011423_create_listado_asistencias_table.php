<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listado_asistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->nullable()->constrained('estudiantes')->onDelete('set null');
            $table->foreignId('asistencia_id')->nullable()->constrained('asistencias')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listado_asistencias');
    }
};
