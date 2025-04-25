<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('peliculas', function (Blueprint $table) {
            $table->string('imagen')->nullable()->after('titulo');
        });
    }
    
    public function down()
    {
        Schema::table('peliculas', function (Blueprint $table) {
            $table->dropColumn('imagen');
        });
    }
};
