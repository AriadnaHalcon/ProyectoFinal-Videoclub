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
        $table->string('dni', 15)->primary();  
        $table->string('nombre', 100);
        $table->string('direccion', 150)->nullable(); 
        $table->string('telefono', 15)->nullable();
        $table->string('email', 100)->unique()->nullable();
        $table->date('fecha_registro')->nullable();
        $table->timestamps();
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
