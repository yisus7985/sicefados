<?php

namespace Modules\AVICONTROL\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductionWeek extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'avicontrol_production_weeks';

    protected $fillable = [
        'nombre',
        'fecha_inicio',
        'fecha_fin',
        'descripcion',
        'estado'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date'
    ];

    protected $dates = [
        'fecha_inicio',
        'fecha_fin',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Scopes
    public function scopeActiva($query)
    {
        return $query->where('estado', 'activa');
    }

    public function scopeInactiva($query)
    {
        return $query->where('estado', 'inactiva');
    }

    // Relaciones
    public function productions()
    {
        return $this->hasMany(Production::class, 'semana_produccion', 'nombre');
    }

    // Accessors
    public function getNombreDisplayAttribute()
    {
        return $this->nombre;
    }

    public function getFechaInicioFormattedAttribute()
    {
        return $this->fecha_inicio ? $this->fecha_inicio->format('d/m/Y') : '';
    }

    public function getFechaFinFormattedAttribute()
    {
        return $this->fecha_fin ? $this->fecha_fin->format('d/m/Y') : '';
    }

    public function getDuracionAttribute()
    {
        if ($this->fecha_inicio && $this->fecha_fin) {
            return $this->fecha_inicio->diffInDays($this->fecha_fin) + 1;
        }
        return 0;
    }
}
