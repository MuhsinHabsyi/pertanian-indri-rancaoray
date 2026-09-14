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
        'recipient_name',
        'recipient_phone',
        'recipient_lat',
        'recipient_lng',
        'delivery_courier',
        'delivery_distance_km',
        'courier_phone',
        'total_payment',
        'shipping_cost',
        'payment_method',
        'order_status',
        'sale_channel',
        'payment_expires_at',
        'nota_number',
    ];

    protected $casts = [
        'payment_expires_at'   => 'datetime',
        'transaction_date'     => 'datetime',
        'recipient_lat'        => 'decimal:7',
        'recipient_lng'        => 'decimal:7',
        'delivery_distance_km' => 'decimal:2',
    ];

    /** Cek apakah pembayaran sudah hangus */
    public function isPaymentExpired(): bool
    {
        return $this->order_status === 'Pending'
            && $this->payment_expires_at !== null
            && now()->isAfter($this->payment_expires_at);
    }

    public function customer() { return $this->belongsTo(User::class, 'customer_id'); }
    public function operational() { return $this->belongsTo(User::class, 'operational_id'); }
    public function items() { return $this->hasMany(OrderItem::class); }
}