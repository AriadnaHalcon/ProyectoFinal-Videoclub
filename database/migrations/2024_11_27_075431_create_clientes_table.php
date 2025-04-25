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
    Schema::create('clientes', function (Blueprint $table) {
        $table->string('dni', 15)->primary();  // Campo dni único y clave primaria
        $table->string('nombre', 100);         // Nombre del cliente
        $table->string('direccion', 150)->nullable(); // Dirección del cliente
        $table->string('telefono', 15)->nullable();  // Teléfono del cliente
        $table->string('email', 100)->unique()->nullable(); // Email debe ser único
        $table->date('fecha_registro')->nullable(); // Fecha de registro del cliente
        $table->timestamps(); // Crea columnas 'created_at' y 'updated_at'
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
