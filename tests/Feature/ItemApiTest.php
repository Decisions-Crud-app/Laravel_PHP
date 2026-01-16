<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // Public API tests (no auth required)
    public function test_can_list_items(): void
    {
        Item::factory()->count(3)->create();
        $this->getJson('/api/items')->assertOk()->assertJsonCount(3);
    }

    public function test_can_list_items_filtered(): void
    {
        Item::factory()->create(['status' => 'active']);
        Item::factory()->count(2)->create(['status' => 'inactive']);
        $this->getJson('/api/items?status=active')->assertOk()->assertJsonCount(1);
    }

    public function test_can_create_item(): void
    {
        $this->postJson('/api/items', [
            'name' => 'New Item',
            'code' => 'NEW001',
            'description' => 'Test',
            'status' => 'active'
        ])->assertCreated();
    }

    public function test_create_duplicate_code(): void
    {
        Item::factory()->create(['code' => 'DUP']);
        $this->postJson('/api/items', [
            'name' => 'Item',
            'code' => 'DUP',
            'status' => 'active'
        ])->assertUnprocessable();
    }

    public function test_create_missing_name(): void
    {
        $this->postJson('/api/items', ['code' => 'X', 'status' => 'active'])
            ->assertUnprocessable();
    }

    public function test_create_missing_code(): void
    {
        $this->postJson('/api/items', ['name' => 'Item', 'status' => 'active'])
            ->assertUnprocessable();
    }

    public function test_create_missing_status(): void
    {
        $this->postJson('/api/items', ['name' => 'Item', 'code' => 'TEST'])
            ->assertUnprocessable();
    }

    public function test_create_invalid_status(): void
    {
        $this->postJson('/api/items', [
            'name' => 'Item',
            'code' => 'TEST',
            'status' => 'unknown'
        ])->assertUnprocessable();
    }

    public function test_can_update_item(): void
    {
        $item = Item::factory()->create();
        $this->putJson('/api/items/' . $item->uuid, [
            'name' => 'Updated',
            'code' => $item->code,
            'status' => 'inactive'
        ])->assertOk();
    }

    public function test_update_missing_name(): void
    {
        $item = Item::factory()->create();
        $this->putJson('/api/items/' . $item->uuid, [
            'code' => $item->code,
            'status' => 'active'
        ])->assertUnprocessable();
    }

    public function test_update_invalid_status(): void
    {
        $item = Item::factory()->create();
        $this->putJson('/api/items/' . $item->uuid, [
            'name' => 'Updated',
            'code' => $item->code,
            'status' => 'invalid'
        ])->assertUnprocessable();
    }

    public function test_can_delete_item(): void
    {
        $item = Item::factory()->create();
        $this->deleteJson('/api/items/' . $item->uuid)->assertStatus(204);
    }

    public function test_delete_nonexistent_item(): void
    {
        $uuid = '00000000-0000-0000-0000-000000000000';
        $this->deleteJson('/api/items/' . $uuid)->assertNotFound();
    }
}


