<?php

namespace Modules\AVICONTROL\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PoultryFacility extends Model
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

    // Relation with daily production (to be implemented in the future)
    public function dailyProduction()
    {
        return $this->hasMany(DailyProduction::class);
    }

    // Calculate area of the poultry facility
    public function getAreaAttribute()
    {
        return $this->length * $this->width;
    }

    // Calculate volume of the poultry facility
    public function getVolumeAttribute()
    {
        return $this->length * $this->width * $this->height;
    }

    // Calculate bird density (birds per square meter)
    public function getDensityAttribute()
    {
        $area = $this->getAreaAttribute();
        return $area > 0 ? $this->capacity / $area : 0;
    }

    // Relación con las aves
    public function birds()
    {
        return $this->hasMany(Bird::class, 'poultry_facility_id');
    }

    // Relación con aves activas
    public function activeBirds()
    {
        return $this->hasMany(Bird::class, 'poultry_facility_id')->where('status', 'active');
    }

    // Calcular el total de aves activas
    public function getTotalActiveBirdsAttribute()
    {
        return $this->activeBirds()->sum('quantity');
    }

    // Calcular el porcentaje de ocupación
    public function getOccupancyPercentageAttribute()
    {
        if ($this->capacity == 0) {
            return 0;
        }
        
        return round(($this->total_active_birds / $this->capacity) * 100, 2);
    }

    // Verificar si el galpón está disponible para más aves
    public function getAvailableCapacityAttribute()
    {
        return $this->capacity - $this->total_active_birds;
    }

    // Verificar si el galpón puede acomodar cierta cantidad de aves
    public function canAccommodate($quantity)
    {
        return $this->available_capacity >= $quantity;
    }
}
