<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;


class Property extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'property_number', 'title_deed_number', 'land_piece_number', 'plan_number', 'sale_date'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class);
    }
    //
}
