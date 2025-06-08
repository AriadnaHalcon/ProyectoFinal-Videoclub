<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Eliminar la foreign key de alquileres
        DB::statement('ALTER TABLE alquileres DROP FOREIGN KEY alquileres_ibfk_1');
        

        // Modificar la columna dni en clientes
        Schema::table('clientes', function (Blueprint $table) {
            $table->char('dni', 9)->change();
            $table->string('telefono', 9)->change();
        });
        // Modificar la columna dni_cliente en carrito
        Schema::table('carrito', function (Blueprint $table) {
            $table->char('dni_cliente', 9)->change();
        });

        DB::statement('ALTER TABLE alquileres ADD CONSTRAINT alquileres_ibfk_1 FOREIGN KEY (dni_cliente) REFERENCES clientes(dni)');
        DB::statement('ALTER TABLE carrito ADD CONSTRAINT carrito_ibfk_1 FOREIGN KEY (dni_cliente) REFERENCES clientes(dni)');
        DB::statement("ALTER TABLE clientes ADD CONSTRAINT chk_dni_formato CHECK (dni REGEXP '^[0-9]{8}[A-Za-z]$')");
        DB::statement("ALTER TABLE clientes ADD CONSTRAINT chk_telefono_formato CHECK (telefono REGEXP '^[0-9]+$')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE clientes DROP CONSTRAINT chk_dni_formato");
        DB::statement("ALTER TABLE clientes DROP CONSTRAINT chk_telefono_formato");
        DB::statement('ALTER TABLE alquileres DROP FOREIGN KEY alquileres_ibfk_1');
        DB::statement('ALTER TABLE carrito DROP FOREIGN KEY carrito_ibfk_1');
        DB::statement('ALTER TABLE alquileres ADD CONSTRAINT alquileres_ibfk_1 FOREIGN KEY (dni_cliente) REFERENCES clientes(dni)');
        DB::statement('ALTER TABLE carrito ADD CONSTRAINT carrito_ibfk_1 FOREIGN KEY (dni_cliente) REFERENCES clientes(dni)');
    }
};
