<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id', 
        'operational_id', 
        'transaction_date', 
        'shipping_address', 
        'total_payment', 
        'shipping_cost',    
        'payment_method', 
        'order_status',
        'sale_channel'
    ];
    public function customer() { return $this->belongsTo(User::class, 'customer_id'); }
    public function operational() { return $this->belongsTo(User::class, 'operational_id'); }
    public function items() { return $this->hasMany(OrderItem::class); }
}