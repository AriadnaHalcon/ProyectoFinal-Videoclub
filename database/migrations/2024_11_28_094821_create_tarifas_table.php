<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tarifas', function (Blueprint $table) {
            $table->id('id_tarifa');
            $table->string('nombre', 50)->unique();
            $table->decimal('descuento', 5, 2)->default(0.00);
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('tarifas');
    }
};
