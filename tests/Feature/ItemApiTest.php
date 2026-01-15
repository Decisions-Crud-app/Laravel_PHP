<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ItemApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_items()
    {
        Sanctum::actingAs(User::factory()->create());
        Item::factory()->count(2)->create();

        $this->getJson('/api/items')
            ->assertOk()
            ->assertJsonCount(2);
    }

    public function test_can_create_item()
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/items', [
            'name' => 'Test',
            'code' => 'ABC123',
            'status' => 'active'
        ])->assertCreated();
    }
}
