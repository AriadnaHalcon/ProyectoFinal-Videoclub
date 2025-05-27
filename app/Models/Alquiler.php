<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alquiler extends Model
{
    use HasFactory;

    protected $table = 'alquileres';
    protected $primaryKey = 'id_alquiler';

    // Desactivar timestamps si no los necesitas
    public $timestamps = false;

    protected $fillable = [
        'dni_cliente', 'id_pelicula', 'fecha_alquiler', 'fecha_devolucion', 'estado', 'precio_rebajado'
    ];

    // Relación con Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'dni_cliente');
    }

    // Relación con Pelicula
    public function pelicula()
    {
        return $this->belongsTo(Pelicula::class, 'id_pelicula');
    }

    // Método para calcular el precio con descuento
    public static function calcularPrecioConDescuento($clienteId, $peliculaId)
    {
        $cliente = Cliente::find($clienteId); // Obtener cliente por su DNI
        $pelicula = Pelicula::find($peliculaId); // Obtener película por su ID
        $tarifa = $cliente->tarifa; // Obtener la tarifa del cliente

        // Calcular el precio con el descuento aplicado
        $precioConDescuento = $pelicula->precio - ($pelicula->precio * ($tarifa->descuento / 100));
        return $precioConDescuento;
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($alquiler) {
            if ($alquiler->estado === 'Pendiente') {
                $alquiler->pelicula->decrement('stock');
            }
        });

        static::updated(function ($alquiler) {
            if ($alquiler->isDirty('estado')) {
                if ($alquiler->estado === 'Devuelta') {
                    $alquiler->pelicula->increment('stock');
                } elseif ($alquiler->estado === 'Pendiente') {
                    $alquiler->pelicula->decrement('stock');
                }
            }
        });

        static::deleted(function ($alquiler) {
            if ($alquiler->estado === 'Pendiente') {
                $alquiler->pelicula->increment('stock');
            }
        });
    }
}