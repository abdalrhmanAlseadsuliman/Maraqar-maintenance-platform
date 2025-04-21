<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MaintenanceSolutionImages;
use App\Models\MaintenanceRequestImages;
use App\Enums\RequestType;
use Filament\Notifications\Notification;



class MaintenanceRequests extends Model
{
    use HasFactory;
    protected $casts = [
        'request_type' => RequestType::class,
    ];

    protected $fillable = [
        'property_id', 'request_type', 'status', 'submitted_at',
        'technician_visits', 'problem_description', 'technician_notes',
        'rejection_reason', 'technician_name', 'cost',
        'executive_director_notes',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function images()
    {
        return $this->hasMany(MaintenanceRequestImages::class, 'maintenance_request_id');
    }


    public function solutionImages()
    {
        return $this->hasMany(MaintenanceSolutionImages::class, 'maintenance_request_id');
    }
    //
}
