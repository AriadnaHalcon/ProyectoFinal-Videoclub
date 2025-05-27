<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'dni';
    public $incrementing = false; 
    protected $fillable = ['dni', 'nombre', 'direccion', 'telefono', 'email', 'fecha_registro', 'id_tarifa'];
    public $timestamps = false;

    public function tarifa()
    {
        return $this->belongsTo(Tarifa::class, 'id_tarifa');
    }
}