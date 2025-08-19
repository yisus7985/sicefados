<?php

namespace Modules\AVICONTROL\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Galpon extends Model
{
    use SoftDeletes;

    protected $table = 'avicontrol_poultry_facilities';

    protected $fillable = [
        'name',
        'length',
        'width',
        'height',
        'capacity',
        'description',
        'status',
        'creation_date'
    ];

    protected $dates = [
        'creation_date',
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
        return $this->length * $this->width;
    }

    public function getVolumenAttribute()
    {
        return $this->length * $this->width * $this->height;
    }

    public function getDensidadAttribute()
    {
        $area = $this->getAreaAttribute();
        return $area > 0 ? $this->capacity / $area : 0;
    }
}
