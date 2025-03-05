<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MaintenanceRequest;

class MaintenanceSolutionImages extends Model
{
    use HasFactory;

    protected $fillable = ['maintenance_request_id', 'image_path'];

    public function maintenanceRequest()
    {
        return $this->belongsTo(MaintenanceRequest::class);
    }
}
