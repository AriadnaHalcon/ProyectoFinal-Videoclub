<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarifa extends Model
{
    use HasFactory;

    protected $table = 'tarifas'; 
    protected $primaryKey = 'id_tarifa'; 
    public $timestamps = false; 

    protected $fillable = [
        'nombre',
        'descuento',
    ];

    // Relación con la tabla 'clientes'
    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'id_tarifa', 'id_tarifa'); 
    }
}
