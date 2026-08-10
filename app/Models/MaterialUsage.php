<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MaterialUsage extends Model
{
    protected $fillable = ['production_schedule_id', 'product_id', 'usage_date', 'quantity_used'];
    public function productionSchedule() { return $this->belongsTo(ProductionSchedule::class); }
    public function product() { return $this->belongsTo(Product::class); }
}