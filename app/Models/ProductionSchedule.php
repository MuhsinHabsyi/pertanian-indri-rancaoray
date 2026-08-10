<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionSchedule extends Model
{
    // Tambahkan crop_season dan estimated_harvest ke dalam daftar ini
    protected $fillable = [
        'user_id', 
        'planting_start_date', 
        'crop_season', 
        'fertilizing_date', 
        'estimated_harvest_date', 
        'estimated_harvest', 
        'actual_harvest_date', 
        'total_harvest', 
        'status'
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function materialUsages() { return $this->hasMany(MaterialUsage::class); }
}