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

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    public function tarifa()
    {
        return $this->belongsTo(Tarifa::class);
    }

    public function alquileres()
    {
        return $this->hasMany(Alquiler::class, 'id_pelicula');
    }
}
