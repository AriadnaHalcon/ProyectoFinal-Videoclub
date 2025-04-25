<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    public $timestamps = false;

    // Definir el nombre de la tabla si no coincide con el plural de la clase
    protected $table = 'categorias'; // Opcional, solo si la tabla no es el plural de la clase

    // Definir la clave primaria si no es 'id'
    protected $primaryKey = 'id_categoria';

    // Definir los campos que se pueden llenar masivamente
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
