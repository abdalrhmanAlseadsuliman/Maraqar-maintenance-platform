<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MaintenanceRequests;

class MaintenanceRequestImages extends Model
{
    use HasFactory;

    protected $fillable = ['maintenance_request_id', 'image_path'];

    public function maintenanceRequest()
    {
        return $this->belongsTo(MaintenanceRequests::class, 'maintenance_request_id');
    }

    protected static function boot()
{
    parent::boot();

    static::creating(function ($image) {
        if (!$image->maintenance_request_id) {
            $image->maintenance_request_id = request()->route('record') ?? null;
        }
    });
}
}
