<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category', 
        'name', 
        'unit', 
        'price', 
        'stock_available', 
        'online_name', 
        'online_price', 
        'online_stock', 
        'image_path', 
        'is_for_sale_online'
    ];

    public function purchaseItems() { return $this->hasMany(PurchaseItem::class); }
    public function materialUsages() { return $this->hasMany(MaterialUsage::class); }
    public function orderItems() { return $this->hasMany(OrderItem::class); }
    public function packages() { return $this->hasMany(ProductPackage::class); }
}