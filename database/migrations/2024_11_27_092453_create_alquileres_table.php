<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('alquileres', function (Blueprint $table) {
        $table->id('id_alquiler');
        $table->string('dni_cliente');  // relacion con la tabla `clientes`
        $table->foreign('dni_cliente')->references('dni')->on('clientes')->onDelete('cascade');
        $table->integer('id_pelicula');  // relacion con la tabla `peliculas`
        $table->foreign('id_pelicula')->references('id_pelicula')->on('peliculas')->onDelete('cascade');
        $table->date('fecha_alquiler')->default(DB::raw('CURRENT_DATE'));
        $table->date('fecha_devolucion')->nullable();
        $table->enum('estado', ['Pendiente', 'Devuelto'])->default('Pendiente');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alquileres');
    }
};
