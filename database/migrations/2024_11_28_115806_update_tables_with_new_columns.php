<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('clientes', function (Blueprint $table) {
        $table->unsignedBigInteger('id_tarifa')->nullable()->after('fecha_registro');
        $table->foreign('id_tarifa')->references('id_tarifa')->on('tarifas')->onDelete('set null');
    });

    Schema::table('peliculas', function (Blueprint $table) {
        $table->decimal('precio', 10, 2)->default(0.00)->after('imagen');
    });

    Schema::table('alquileres', function (Blueprint $table) {
        $table->decimal('precio_rebajado', 10, 2)->nullable()->after('estado');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
