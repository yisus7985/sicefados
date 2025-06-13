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

    // Relación con producción diaria (a futuro)
    public function produccionDiaria()
    {
        return $this->hasMany(ProduccionDiaria::class);
    }

    public function getAreaAttribute()
    {
        return $this->largo * $this->ancho;
    }

    public function getVolumenAttribute()
    {
        return $this->largo * $this->ancho * $this->alto;
    }

    public function getDensidadAttribute()
    {
        $area = $this->getAreaAttribute();
        return $area > 0 ? $this->capacidad / $area : 0;
    }
}
