<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MaintenanceRequests;

class MaintenanceSolutionImages extends Model
{
    use HasFactory;

    protected $fillable = ['maintenance_request_id', 'image_path'];

    public function maintenanceRequest()
    {
        return $this->belongsTo(MaintenanceRequests::class, 'maintenance_request_id');
    }
}
