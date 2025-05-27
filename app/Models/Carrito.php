<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Carrito extends Model
{
    use HasFactory;

    protected $table = 'carritos';
    public $timestamps = false;

    protected $fillable = [
        'dni_cliente',
        'id_pelicula',
        'cantidad',
        'fecha_agregado',
    ];

    public function pelicula()
    {
        return $this->belongsTo(Pelicula::class, 'id_pelicula');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'dni_cliente', 'dni');
    }
}
