<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelicula extends Model
{
    use HasFactory;

    protected $table = 'peliculas';
    protected $primaryKey = 'id_pelicula';

    public $timestamps = false;

    protected $fillable = [
        'imagen',
        'titulo', 
        'id_categoria', 
        'director', 
        'anio_estreno', 
        'stock',
        'precio',
    ];

    //Relación con la tabla 'categorias'.
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    // Relación con la tabla 'tarifas'.
    public function tarifa()
    {
        return $this->belongsTo(Tarifa::class);
    }

    // Relación con la tabla 'alquileres'.
    public function alquileres()
    {
        return $this->hasMany(Alquiler::class, 'id_pelicula');
    }
}
