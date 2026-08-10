<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseValidationTest extends TestCase
{
    use RefreshDatabase;

    private $owner;
    private $operational;
    private $product;
    private $purchase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Users
        $this->owner = User::create([
            'full_name' => 'Owner User',
            'username' => 'owner',
            'password' => bcrypt('password123'),
            'role' => 'Owner',
        ]);

        $this->operational = User::create([
            'full_name' => 'Operational User',
            'username' => 'operational',
            'password' => bcrypt('password123'),
            'role' => 'Operational',
        ]);

        // Create Product
        $this->product = Product::create([
            'category' => 'Seed',
            'name' => 'Bibit Test',
            'unit' => 'Kg',
            'price' => 10000,
            'stock_available' => 10,
        ]);

        // Create Purchase
        $this->purchase = Purchase::create([
            'user_id' => $this->operational->id,
            'procurement_source' => 'Mandiri',
            'submission_date' => now()->format('Y-m-d'),
            'total_cost' => 100000,
            'validation_status' => 'Pending',
        ]);

        PurchaseItem::create([
            'purchase_id' => $this->purchase->id,
            'product_id' => $this->product->id,
            'quantity' => 10,
            'unit_price' => 10000,
            'subtotal' => 100000,
        ]);
    }

    public function test_operational_user_cannot_validate_purchase()
    {
        $response = $this->actingAs($this->operational, 'internal')
            ->patch("/purchases/{$this->purchase->id}/validate", [
                'action' => 'Approved',
            ]);

        $response->assertStatus(403);
        $this->assertEquals('Pending', $this->purchase->fresh()->validation_status);
    }

    public function test_operational_user_does_not_see_validation_buttons()
    {
        $response = $this->actingAs($this->operational, 'internal')
            ->get('/purchases');

        $response->assertStatus(200);
        $response->assertDontSee('Setujui');
        $response->assertDontSee('Tolak');
    }

    public function test_owner_user_can_validate_purchase()
    {
        $response = $this->actingAs($this->owner, 'internal')
            ->patch("/purchases/{$this->purchase->id}/validate", [
                'action' => 'Approved',
            ]);

        $response->assertRedirect('/purchases');
        $this->assertEquals('Approved', $this->purchase->fresh()->validation_status);
    }

    public function test_owner_user_sees_validation_buttons()
    {
        $response = $this->actingAs($this->owner, 'internal')
            ->get('/purchases');

        $response->assertStatus(200);
        $response->assertSee('Setujui');
        $response->assertSee('Tolak');
    }

    public function test_user_can_upload_receipt_image_when_approved()
    {
        \Illuminate\Support\Facades\Storage::fake('public');

        $this->purchase->update(['validation_status' => 'Approved']);

        $file = \Illuminate\Http\UploadedFile::fake()->image('nota.png');

        $response = $this->actingAs($this->operational, 'internal')
            ->post("/purchases/{$this->purchase->id}/upload-receipt", [
                'receipt_proof' => $file,
            ]);

        $response->assertRedirect('/purchases');
        $freshPurchase = $this->purchase->fresh();
        $this->assertNotNull($freshPurchase->receipt_proof);
        \Illuminate\Support\Facades\Storage::disk('public')->assertExists($freshPurchase->receipt_proof);
    }

    public function test_operational_user_cannot_realize_goods_receipt()
    {
        $this->purchase->update([
            'validation_status' => 'Approved',
            'receipt_proof' => 'receipts/fake_nota.png'
        ]);

        $response = $this->actingAs($this->operational, 'internal')
            ->patch("/purchases/{$this->purchase->id}/realize");

        $response->assertStatus(403);
        $this->assertFalse($this->purchase->fresh()->is_realized);
    }

    public function test_owner_user_can_realize_goods_receipt_and_increment_stock()
    {
        $initialStock = $this->product->stock_available;

        $this->purchase->update([
            'validation_status' => 'Approved',
            'receipt_proof' => 'receipts/fake_nota.png'
        ]);

        $response = $this->actingAs($this->owner, 'internal')
            ->patch("/purchases/{$this->purchase->id}/realize");

        $response->assertRedirect('/purchases');
        $this->assertTrue($this->purchase->fresh()->is_realized);
        $this->assertEquals($initialStock + 10, $this->product->fresh()->stock_available);
    }
}
