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
}