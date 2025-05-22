<?php

namespace Modules\AVICONTROL\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Galpon extends Model
{
    use SoftDeletes;

    protected $table = 'galpones';
    
    protected $fillable = [
        'nombre',
        'largo',
        'ancho',
        'alto',
        'capacidad',
        'descripcion',
        'estado',
        'fecha_creacion'
    ];

    protected $dates = [
        'fecha_creacion',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relación con producción diaria (a implementar en el futuro)
    public function produccionDiaria()
    {
        return $this->hasMany(ProduccionDiaria::class);
    }

    // Calcular área del galpón
    public function getAreaAttribute()
    {
        return $this->largo * $this->ancho;
    }

    // Calcular volumen del galpón
    public function getVolumenAttribute()
    {
        return $this->largo * $this->ancho * $this->alto;
    }

    // Calcular densidad de aves (aves por metro cuadrado)
    public function getDensidadAttribute()
    {
        $area = $this->getAreaAttribute();
        return $area > 0 ? $this->capacidad / $area : 0;
    }
}