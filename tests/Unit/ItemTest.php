<?php

namespace Tests\Unit;

use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_has_correct_attributes(): void
    {
        $item = Item::factory()->create([
            'name' => 'Test Item',
            'code' => 'TEST001',
            'description' => 'Test Description',
            'status' => 'active'
        ]);

        $this->assertEquals('Test Item', $item->name);
        $this->assertEquals('TEST001', $item->code);
        $this->assertEquals('Test Description', $item->description);
        $this->assertEquals('active', $item->status);
        $this->assertNotNull($item->uuid);
    }

    public function test_item_fillable_attributes(): void
    {
        $fillable = (new Item())->getFillable();
        
        $this->assertContains('name', $fillable);
        $this->assertContains('code', $fillable);
        $this->assertContains('description', $fillable);
        $this->assertContains('status', $fillable);
    }

    public function test_item_uses_uuid_as_primary_key(): void
    {
        $item = Item::factory()->create();
        
        $this->assertTrue(is_string($item->uuid));
        $this->assertNotNull($item->uuid);
    }

    public function test_item_can_be_created(): void
    {
        $item = Item::create([
            'name' => 'New Item',
            'code' => 'NEW001',
            'description' => 'New Description',
            'status' => 'inactive'
        ]);

        $this->assertDatabaseHas('items', [
            'name' => 'New Item',
            'code' => 'NEW001'
        ]);
    }

    public function test_item_can_be_updated(): void
    {
        $item = Item::factory()->create(['name' => 'Original Name']);

        $item->update(['name' => 'Updated Name']);

        $this->assertEquals('Updated Name', $item->fresh()->name);
    }

    public function test_item_can_be_deleted(): void
    {
        $item = Item::factory()->create();
        $uuid = $item->uuid;

        $item->delete();

        $this->assertDatabaseMissing('items', ['uuid' => $uuid]);
    }

    public function test_item_factory_creates_valid_item(): void
    {
        $item = Item::factory()->create();

        $this->assertNotNull($item->uuid);
        $this->assertNotNull($item->name);
        $this->assertNotNull($item->code);
        $this->assertNotNull($item->status);
        $this->assertIn($item->status, ['active', 'inactive']);
    }
}
