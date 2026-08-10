<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\ProductPackage;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderPaymentTest extends TestCase
{
    use RefreshDatabase;

    private $customer;
    private $product;
    private $pkg5kg;
    private $pkg10kg;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = User::create([
            'full_name' => 'Customer User',
            'username' => 'customer',
            'password' => bcrypt('password123'),
            'role' => 'Customer',
        ]);

        $this->product = Product::create([
            'category' => 'Rice',
            'name' => 'Beras Pandan Wangi',
            'unit' => 'Kg',
            'price' => 15000,
            'stock_available' => 500,
            'is_for_sale_online' => true,
        ]);

        $this->pkg5kg = ProductPackage::create([
            'product_id' => $this->product->id,
            'package_size' => 5,
            'online_name' => 'Pandan Wangi 5 Kg',
            'online_price' => 75000,
            'online_stock' => 10,
            'is_active' => true,
        ]);

        $this->pkg10kg = ProductPackage::create([
            'product_id' => $this->product->id,
            'package_size' => 10,
            'online_name' => 'Pandan Wangi 10 Kg',
            'online_price' => 140000,
            'online_stock' => 10,
            'is_active' => true,
        ]);
    }

    public function test_payment_success_deducts_from_correct_5kg_package()
    {
        // Order: 2 packs of 5 Kg package (total = 10 Kg, subtotal = 150,000)
        $order = Order::create([
            'customer_id' => $this->customer->id,
            'transaction_date' => now(),
            'shipping_address' => 'Jl. Dipatiukur No. 112, Bandung',
            'shipping_cost' => 15000,
            'total_payment' => 165000,
            'payment_method' => 'Midtrans',
            'order_status' => 'Pending',
            'sale_channel' => 'Online',
        ]);

        $order->items()->create([
            'product_id' => $this->product->id,
            'quantity' => 10, // 2 packs * 5 Kg = 10 Kg
            'unit_price' => 15000, // 75000 / 5 = 15000
            'subtotal' => 150000,
        ]);

        $response = $this->actingAs($this->customer, 'web')
            ->get("/orders/{$order->id}/success");

        $response->assertRedirect('/orders');
        
        $this->assertEquals(8, $this->pkg5kg->fresh()->online_stock); // 10 - 2 = 8
        $this->assertEquals(10, $this->pkg10kg->fresh()->online_stock); // Unchanged
        $this->assertEquals('Paid', $order->fresh()->order_status);
    }

    public function test_payment_success_deducts_from_correct_10kg_package()
    {
        // Order: 1 pack of 10 Kg package (total = 10 Kg, subtotal = 140,000)
        $order = Order::create([
            'customer_id' => $this->customer->id,
            'transaction_date' => now(),
            'shipping_address' => 'Jl. Dipatiukur No. 112, Bandung',
            'shipping_cost' => 15000,
            'total_payment' => 155000,
            'payment_method' => 'Midtrans',
            'order_status' => 'Pending',
            'sale_channel' => 'Online',
        ]);

        $order->items()->create([
            'product_id' => $this->product->id,
            'quantity' => 10, // 1 pack * 10 Kg = 10 Kg
            'unit_price' => 14000, // 140000 / 10 = 14000
            'subtotal' => 140000,
        ]);

        $response = $this->actingAs($this->customer, 'web')
            ->get("/orders/{$order->id}/success");

        $response->assertRedirect('/orders');

        $this->assertEquals(10, $this->pkg5kg->fresh()->online_stock); // Unchanged
        $this->assertEquals(9, $this->pkg10kg->fresh()->online_stock); // 10 - 1 = 9
        $this->assertEquals('Paid', $order->fresh()->order_status);
    }
}
