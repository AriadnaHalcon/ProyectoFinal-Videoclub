<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'categorias';
    protected $primaryKey = 'id_categoria';
    protected $fillable = [
        'nombre', 
        'descripcion'
    ];

    // Relación con el modelo Pelicula
    public function peliculas()
    {
        return $this->hasMany(Pelicula::class, 'id_categoria', 'id_categoria');
    }
}
