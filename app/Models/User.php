<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Hapus nik, phone_number, dan address dari sini
    protected $fillable = ['full_name', 'username', 'password', 'role'];
    
    protected $hidden = ['password', 'remember_token'];
    
    protected function casts(): array { 
        return ['password' => 'hashed']; 
    }

    public function purchases() { return $this->hasMany(Purchase::class); }
    public function productionSchedules() { return $this->hasMany(ProductionSchedule::class); }
    public function ordersAsCustomer() { return $this->hasMany(Order::class, 'customer_id'); }
    public function ordersAsOperational() { return $this->hasMany(Order::class, 'operational_id'); }
}