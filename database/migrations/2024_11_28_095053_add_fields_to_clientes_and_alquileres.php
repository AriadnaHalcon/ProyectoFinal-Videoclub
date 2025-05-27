<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Agregar columna id_tarifa a la tabla clientes
        Schema::table('clientes', function (Blueprint $table) {
            $table->unsignedBigInteger('id_tarifa')->nullable()->after('email');
            $table->foreign('id_tarifa')->references('id_tarifa')->on('tarifas');
        });

        // Agregar columna precio_rebajado a la tabla alquileres
        Schema::table('alquileres', function (Blueprint $table) {
            $table->decimal('precio_rebajado', 10, 2)->nullable()->after('estado');
        });
    }

    public function down()
    {
        // Eliminar columna id_tarifa de clientes
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropForeign(['id_tarifa']);
            $table->dropColumn('id_tarifa');
        });

        // Eliminar columna precio_rebajado de alquileres
        Schema::table('alquileres', function (Blueprint $table) {
            $table->dropColumn('precio_rebajado');
        });
    }
};
