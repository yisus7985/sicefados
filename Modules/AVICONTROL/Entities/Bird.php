<?php

namespace Modules\AVICONTROL\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bird extends Model
{
    use SoftDeletes;

    protected $table = 'avicontrol_birds';
    
    protected $fillable = [
        'poultry_facility_id',
        'galpon_id', // Asegúrate de incluir esto si usarás la relación
        'batch_code',
        'bird_type',
        'quantity',
        'initial_quantity',
        'entry_date',
        'age_weeks',
        'breed',
        'average_weight',
        'status',
        'notes',
        'purchase_price',
        'supplier'
    ];

    protected $dates = [
        'entry_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'entry_date' => 'date',
        'average_weight' => 'decimal:2',
        'purchase_price' => 'decimal:2'
    ];

    // Relación principal con el galpón (poultry_facility_id)
    public function poultryFacility()
    {
        return $this->belongsTo(PoultryFacility::class, 'poultry_facility_id');
    }

    // Relación adicional con otro campo (galpon_id)
    public function galpon()
    {
        return $this->belongsTo(PoultryFacility::class, 'galpon_id');
    }

    // Accessor para obtener el nombre del tipo de ave en español
    public function getBirdTypeNameAttribute()
    {
        $types = [
            'laying_hens' => 'Gallinas Ponedoras',
            'broilers' => 'Pollos de Engorde',
            'chicks' => 'Pollitos',
            'breeders' => 'Reproductores'
        ];
        
        return $types[$this->bird_type] ?? $this->bird_type;
    }

    // Accessor para el nombre del estado
    public function getStatusNameAttribute()
    {
        $statuses = [
            'active' => 'Activo',
            'sold' => 'Vendido',
            'deceased' => 'Fallecido',
            'transferred' => 'Transferido'
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }

    // Edad actual
    public function getCurrentAgeWeeksAttribute()
    {
        if (!$this->age_weeks || !$this->entry_date) {
            return null;
        }

        $weeksFromEntry = $this->entry_date->diffInWeeks(now());
        return $this->age_weeks + $weeksFromEntry;
    }

    // Valor total
    public function getTotalValueAttribute()
    {
        return $this->quantity * ($this->purchase_price ?? 0);
    }

    // Mortalidad
    public function getMortalityAttribute()
    {
        return $this->initial_quantity - $this->quantity;
    }

    public function getMortalityPercentageAttribute()
    {
        if ($this->initial_quantity == 0) {
            return 0;
        }

        return round(($this->mortality / $this->initial_quantity) * 100, 2);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByFacility($query, $facilityId)
    {
        return $query->where('poultry_facility_id', $facilityId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('bird_type', $type);
    }
}
