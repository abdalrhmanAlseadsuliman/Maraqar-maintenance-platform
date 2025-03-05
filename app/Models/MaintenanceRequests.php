<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class MaintenanceRequests extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id', 'request_type', 'status', 'submitted_at',
        'technician_visits', 'problem_description', 'technician_notes',
        'rejection_reason', 'technician_name', 'cost'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function images()
    {
        return $this->hasMany(MaintenanceRequestImage::class);
    }

    public function solutionImages()
    {
        return $this->hasMany(MaintenanceSolutionImage::class);
    }
    //
}
