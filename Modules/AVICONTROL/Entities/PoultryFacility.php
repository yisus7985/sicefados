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

    public function dailyProduction()
    {
        return $this->hasMany(DailyProduction::class);
    }

    public function getAreaAttribute()
    {
        return $this->length * $this->width;
    }

    public function getVolumeAttribute()
    {
        return $this->length * $this->width * $this->height;
    }

    public function getDensityAttribute()
    {
        $area = $this->getAreaAttribute();
        return $area > 0 ? $this->capacity / $area : 0;
    }

    // Relación principal con las aves
    public function birds()
    {
        return $this->hasMany(Bird::class, 'poultry_facility_id');
    }

    public function activeBirds()
    {
        return $this->hasMany(Bird::class, 'poultry_facility_id')->where('status', 'active');
    }

    public function getTotalActiveBirdsAttribute()
    {
        return $this->activeBirds()->sum('quantity');
    }

    public function getOccupancyPercentageAttribute()
    {
        if ($this->capacity == 0) {
            return 0;
        }
        
        return round(($this->total_active_birds / $this->capacity) * 100, 2);
    }

    public function getAvailableCapacityAttribute()
    {
        return $this->capacity - $this->total_active_birds;
    }

    public function canAccommodate($quantity)
    {
        return $this->available_capacity >= $quantity;
    }

    // Relación adicional con aves usando el campo 'galpon_id'
    public function aves()
    {
        return $this->hasMany(Bird::class, 'galpon_id');
    }
}
