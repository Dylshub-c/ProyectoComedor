<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('listado_asistencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estudiante_id');
            $table->unsignedBigInteger('asistencia_id');
            $table->string('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('estudiante_id')->references('id')->on('estudiantes')->onDelete('cascade');
            $table->foreign('asistencia_id')->references('id')->on('asistencias')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listado_asistencias');
    }
};