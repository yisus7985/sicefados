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
        'tipo',
        'length',
        'width',
        'height',
        'capacity',
        'description',
        'status',
        'creation_date'
    ];

    protected $casts = [
        'creation_date' => 'date'
    ];

    protected $dates = [
        'creation_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Relación con producción
    public function productions()
    {
        return $this->hasMany(Production::class, 'galpon_id');
    }

    // Relación con producción diaria (a futuro)
    public function produccionDiaria()
    {
        return $this->hasMany(ProduccionDiaria::class);
    }

    // Scopes
    public function scopeGallinasPonedoras($query)
    {
        return $query->where('tipo', 'gallinas_ponedoras');
    }

    public function scopePollosEngorde($query)
    {
        return $query->where('tipo', 'pollos_engorde');
    }

    public function scopeActivo($query)
    {
        return $query->where('status', 'active');
    }

    // Accessors
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

    public function getTipoDisplayAttribute()
    {
        return $this->tipo === 'gallinas_ponedoras' ? 'Gallinas Ponederas' : 'Pollos de Engorde';
    }

    public function getTipoProduccionAttribute()
    {
        return $this->tipo === 'gallinas_ponedoras' ? 'huevos' : 'carne';
    }
}
