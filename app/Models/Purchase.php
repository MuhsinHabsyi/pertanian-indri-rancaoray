<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'user_id', 
        'procurement_source', 
        'submission_date', 
        'total_cost', 
        'validation_status', 
        'receipt_proof',
        'is_realized'
    ];

    protected $casts = [
        'is_realized' => 'boolean',
    ];
    
    public function user() { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(PurchaseItem::class); }
}