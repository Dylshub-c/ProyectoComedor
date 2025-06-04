<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id('idPersona');

            $table->string('Nombre', 100);
            $table->string('PrimerApellido', 100);
            $table->string('SegundoApellido', 100);
            $table->string('Cedula', 20)->unique();
            $table->string('TipoUsuario', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
