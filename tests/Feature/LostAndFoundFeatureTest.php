<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Claim;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LostAndFoundFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $user1;
    protected User $user2;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->user1 = User::factory()->create(['role' => 'user']);
        $this->user2 = User::factory()->create(['role' => 'user']);

        $this->category = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
        ]);
    }

    public function test_guest_can_view_items_feed(): void
    {
        Item::create([
            'user_id' => $this->user1->id,
            'category_id' => $this->category->id,
            'type' => 'lost',
            'title' => 'Lost AirPods Case',
            'description' => 'Left on bench outside cafeteria',
            'location' => 'Cafeteria Bench',
            'event_date' => now()->toDateString(),
            'status' => 'open',
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Lost AirPods Case');
    }

    public function test_authenticated_user_can_post_item(): void
    {
        $response = $this->actingAs($this->user1)->post('/items', [
            'type' => 'found',
            'title' => 'Found Blue Hydro Flask',
            'category_id' => $this->category->id,
            'description' => 'Found in Library 2nd floor with stickers',
            'location' => 'Library 2nd Floor',
            'event_date' => now()->toDateString(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('items', [
            'title' => 'Found Blue Hydro Flask',
            'user_id' => $this->user1->id,
            'status' => 'open',
        ]);
    }

    public function test_user_cannot_edit_another_users_item(): void
    {
        $item = Item::create([
            'user_id' => $this->user1->id,
            'category_id' => $this->category->id,
            'type' => 'lost',
            'title' => 'User1 Item',
            'description' => 'Description here',
            'location' => 'Auditorium',
            'event_date' => now()->toDateString(),
            'status' => 'open',
        ]);

        // User2 tries to edit User1's item -> 403 Forbidden
        $response = $this->actingAs($this->user2)->get("/items/{$item->id}/edit");
        $response->assertStatus(403);
    }

    public function test_user_can_submit_claim_on_open_item(): void
    {
        $item = Item::create([
            'user_id' => $this->user1->id,
            'category_id' => $this->category->id,
            'type' => 'found',
            'title' => 'Found Wallet',
            'description' => 'Leather wallet found in gym',
            'location' => 'Campus Gym',
            'event_date' => now()->toDateString(),
            'status' => 'open',
        ]);

        $response = $this->actingAs($this->user2)->post("/items/{$item->id}/claims", [
            'message' => 'This wallet is mine! It has my KTM ID card and driver license.',
            'contact' => '+6281234567890',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('claims', [
            'item_id' => $item->id,
            'user_id' => $this->user2->id,
            'status' => 'pending',
        ]);
    }

    public function test_poster_can_approve_claim_and_auto_reject_other_claims(): void
    {
        $user3 = User::factory()->create();

        $item = Item::create([
            'user_id' => $this->user1->id,
            'category_id' => $this->category->id,
            'type' => 'found',
            'title' => 'Found Calculator',
            'description' => 'Casio calculator found in classroom',
            'location' => 'Room 101',
            'event_date' => now()->toDateString(),
            'status' => 'open',
        ]);

        $claim1 = Claim::create([
            'item_id' => $item->id,
            'user_id' => $this->user2->id,
            'message' => 'My calculator with my name sticker at the back',
            'contact' => '08123456789',
            'status' => 'pending',
        ]);

        $claim2 = Claim::create([
            'item_id' => $item->id,
            'user_id' => $user3->id,
            'message' => 'I think this might be my calculator as well',
            'contact' => '08987654321',
            'status' => 'pending',
        ]);

        // Poster approves claim1
        $response = $this->actingAs($this->user1)->patch("/claims/{$claim1->id}/approve");
        $response->assertRedirect();

        $this->assertEquals('approved', $claim1->fresh()->status);
        $this->assertEquals('rejected', $claim2->fresh()->status);
        $this->assertEquals('claimed', $item->fresh()->status);
    }

    public function test_poster_can_mark_claimed_item_as_returned(): void
    {
        $item = Item::create([
            'user_id' => $this->user1->id,
            'category_id' => $this->category->id,
            'type' => 'found',
            'title' => 'Found Item',
            'description' => 'Item description',
            'location' => 'Location',
            'event_date' => now()->toDateString(),
            'status' => 'claimed',
        ]);

        $response = $this->actingAs($this->user1)->patch("/items/{$item->id}/returned");
        $response->assertRedirect();
        $this->assertEquals('returned', $item->fresh()->status);
    }

    public function test_non_admin_cannot_access_category_management(): void
    {
        $response = $this->actingAs($this->user1)->get('/admin/categories');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_and_create_categories(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/categories', [
            'name' => 'Musical Instruments',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', [
            'name' => 'Musical Instruments',
            'slug' => 'musical-instruments',
        ]);
    }
}
