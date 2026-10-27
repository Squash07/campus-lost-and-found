<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Claim;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Business rules, edge cases and page rendering for every feature (F1 to F11).
 */
class LostAndFoundRulesTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $poster;
    protected User $other;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->poster = User::factory()->create(['name' => 'Poster Person']);
        $this->other = User::factory()->create(['name' => 'Other Person']);
        $this->category = Category::create(['name' => 'Electronics', 'slug' => 'electronics']);
    }

    private function item(array $overrides = []): Item
    {
        return Item::create(array_merge([
            'user_id' => $this->poster->id,
            'category_id' => $this->category->id,
            'type' => 'found',
            'title' => 'Black AirPods case',
            'description' => 'Found on a library table',
            'location' => 'Central Library',
            'event_date' => now()->toDateString(),
            'status' => 'open',
        ], $overrides));
    }

    private function claim(Item $item, User $by, string $status = 'pending'): Claim
    {
        return Claim::create([
            'item_id' => $item->id,
            'user_id' => $by->id,
            'message' => 'This is mine, the case has my initials engraved on it.',
            'contact' => 'WA 0812',
            'status' => $status,
        ]);
    }

    // ---- Routing / pages ---------------------------------------------------------------

    public function test_post_item_page_is_not_swallowed_by_the_show_route(): void
    {
        $this->actingAs($this->poster)->get('/items/create')
            ->assertOk()
            ->assertSee('Post an item');
    }

    public function test_guest_is_sent_to_login_for_post_item_page(): void
    {
        $this->get('/items/create')->assertRedirect('/login');
    }

    public function test_every_main_page_renders(): void
    {
        $item = $this->item();
        $this->claim($item, $this->other);

        $this->get('/')->assertOk();
        $this->get("/items/{$item->id}")->assertOk();
        $this->get('/login')->assertOk();
        $this->get('/register')->assertOk();

        $this->actingAs($this->poster);
        $this->get("/items/{$item->id}")->assertOk()->assertSee('Approve');
        $this->get("/items/{$item->id}/edit")->assertOk();
        $this->get('/dashboard')->assertOk();
        $this->get('/profile')->assertOk();

        $this->actingAs($this->admin);
        $this->get('/admin/categories')->assertOk();
        $this->get('/admin/categories/create')->assertOk();
        $this->get("/admin/categories/{$this->category->id}/edit")->assertOk();
    }

    public function test_missing_item_shows_404_page(): void
    {
        $this->get('/items/999999')->assertNotFound()->assertSee("couldn't find that page");
    }

    public function test_malformed_feed_query_does_not_crash(): void
    {
        $this->get('/?q[]=x')->assertRedirect('/');
        $this->get('/?status[]=x')->assertRedirect('/');
        $this->get('/?type=stolen')->assertRedirect('/');
    }

    public function test_page_title_is_escaped_only_once(): void
    {
        $item = $this->item(['title' => 'Tote bag "Stay Curious"']);

        $html = $this->get("/items/{$item->id}")->getContent();
        $this->assertStringContainsString('<title>Tote bag &quot;Stay Curious&quot; |', $html);
        $this->assertStringNotContainsString('&amp;quot;', $html);
    }

    public function test_demo_account_box_is_hidden_outside_local_unless_enabled(): void
    {
        config(['app.show_demo_accounts' => false]);
        $this->get('/login')->assertDontSee('Demo accounts');

        config(['app.show_demo_accounts' => true]);
        $this->get('/login')->assertSee('Demo accounts');
    }

    public function test_second_approval_on_same_item_is_refused(): void
    {
        $item = $this->item();
        $first = $this->claim($item, $this->other);
        $second = $this->claim($item, User::factory()->create());

        $this->actingAs($this->poster)->patch("/claims/{$first->id}/approve");
        // Simulate a stale second tab: force the claim back to pending, then approve it.
        $second->update(['status' => 'pending']);
        $this->actingAs($this->poster)->patch("/claims/{$second->id}/approve")->assertSessionHas('error');

        $this->assertSame(1, Claim::where('item_id', $item->id)->where('status', 'approved')->count());
    }

    // ---- F1 / F4 / F5: item CRUD -------------------------------------------------------

    public function test_post_item_with_photo_stores_file_in_uploads(): void
    {
        Storage::fake('uploads');

        $this->actingAs($this->poster)->post('/items', [
            'type' => 'lost',
            'title' => 'Navy backpack',
            'category_id' => $this->category->id,
            'description' => 'Herschel backpack with a laptop sleeve',
            'location' => 'Classroom 302',
            'event_date' => now()->toDateString(),
            'photo' => UploadedFile::fake()->image('bag.jpg', 800, 600),
        ])->assertRedirect();

        $item = Item::where('title', 'Navy backpack')->firstOrFail();
        $this->assertNotNull($item->photo_path);
        Storage::disk('uploads')->assertExists($item->photo_path);
    }

    public function test_post_item_validation_rejects_bad_input(): void
    {
        $this->actingAs($this->poster)->post('/items', [
            'type' => 'stolen',
            'title' => '',
            'category_id' => 9999,
            'description' => '',
            'location' => '',
            'event_date' => now()->addDays(3)->toDateString(),
            'photo' => UploadedFile::fake()->create('virus.pdf', 100, 'application/pdf'),
        ])->assertSessionHasErrors(['type', 'title', 'category_id', 'description', 'location', 'event_date', 'photo']);

        $this->assertDatabaseCount('items', 0);
    }

    public function test_photo_over_2mb_is_rejected(): void
    {
        $this->actingAs($this->poster)->post('/items', [
            'type' => 'found', 'title' => 'Big photo', 'category_id' => $this->category->id,
            'description' => 'x', 'location' => 'y', 'event_date' => now()->toDateString(),
            'photo' => UploadedFile::fake()->image('big.jpg')->size(3000),
        ])->assertSessionHasErrors('photo');
    }

    public function test_owner_can_update_item_but_others_cannot(): void
    {
        $item = $this->item();
        $payload = [
            'type' => 'found', 'title' => 'Updated title', 'category_id' => $this->category->id,
            'description' => 'Updated', 'location' => 'Library', 'event_date' => now()->toDateString(),
        ];

        $this->actingAs($this->other)->put("/items/{$item->id}", $payload)->assertForbidden();
        $this->assertSame('Black AirPods case', $item->fresh()->title);

        $this->actingAs($this->poster)->put("/items/{$item->id}", $payload)->assertRedirect();
        $this->assertSame('Updated title', $item->fresh()->title);
    }

    public function test_delete_rules_owner_and_admin_yes_others_no(): void
    {
        $item = $this->item();
        $this->claim($item, $this->other);

        $this->actingAs($this->other)->delete("/items/{$item->id}")->assertForbidden();
        $this->assertModelExists($item);

        $this->actingAs($this->admin)->delete("/items/{$item->id}")->assertRedirect();
        $this->assertModelMissing($item);
        $this->assertDatabaseCount('claims', 0); // cascade
    }

    // ---- F2 / F6: feed and filters -----------------------------------------------------

    public function test_feed_filters_by_keyword_type_category_and_status(): void
    {
        $bags = Category::create(['name' => 'Bags', 'slug' => 'bags']);
        $this->item(['title' => 'Silver laptop', 'type' => 'lost']);
        $this->item(['title' => 'Red umbrella', 'type' => 'found', 'status' => 'returned']);
        $this->item(['title' => 'Green tote', 'category_id' => $bags->id]);

        $this->get('/?q=laptop')->assertSee('Silver laptop')->assertDontSee('Red umbrella');
        $this->get('/?type=lost')->assertSee('Silver laptop')->assertDontSee('Green tote');
        $this->get('/?category=' . $bags->id)->assertSee('Green tote')->assertDontSee('Silver laptop');
        $this->get('/?status=returned')->assertSee('Red umbrella')->assertDontSee('Green tote');
        $this->get('/?q=doesnotexist')->assertSee('No items match these filters');
    }

    // ---- F7: claims --------------------------------------------------------------------

    public function test_cannot_claim_own_item(): void
    {
        $item = $this->item();

        $this->actingAs($this->poster)->post("/items/{$item->id}/claims", [
            'message' => 'Trying to claim my own post, which should not work.', 'contact' => 'x',
        ])->assertRedirect()->assertSessionHas('error');

        $this->assertDatabaseCount('claims', 0);
    }

    public function test_cannot_claim_twice_or_claim_a_closed_item(): void
    {
        $item = $this->item();
        $this->claim($item, $this->other);

        $this->actingAs($this->other)->post("/items/{$item->id}/claims", [
            'message' => 'Second attempt at claiming the same item again.', 'contact' => 'x',
        ])->assertSessionHas('error');
        $this->assertDatabaseCount('claims', 1);

        $closed = $this->item(['status' => 'claimed']);
        $this->actingAs($this->other)->post("/items/{$closed->id}/claims", [
            'message' => 'Claiming an item that already has an approved claim.', 'contact' => 'x',
        ])->assertSessionHas('error');
        $this->assertDatabaseMissing('claims', ['item_id' => $closed->id]);
    }

    public function test_claim_message_must_be_at_least_20_characters(): void
    {
        $item = $this->item();

        $this->actingAs($this->other)->post("/items/{$item->id}/claims", [
            'message' => 'too short', 'contact' => '',
        ])->assertSessionHasErrors(['message', 'contact']);
    }

    public function test_guest_cannot_claim(): void
    {
        $item = $this->item();
        $this->post("/items/{$item->id}/claims", ['message' => str_repeat('a', 30), 'contact' => 'x'])
            ->assertRedirect('/login');
    }

    // ---- F8: review claims -------------------------------------------------------------

    public function test_only_poster_can_approve_or_reject(): void
    {
        $item = $this->item();
        $claim = $this->claim($item, $this->other);

        $this->actingAs($this->other)->patch("/claims/{$claim->id}/approve")->assertForbidden();
        $this->actingAs($this->admin)->patch("/claims/{$claim->id}/reject")->assertForbidden();
        $this->assertSame('pending', $claim->fresh()->status);
    }

    public function test_reject_keeps_item_open(): void
    {
        $item = $this->item();
        $claim = $this->claim($item, $this->other);

        $this->actingAs($this->poster)->patch("/claims/{$claim->id}/reject")->assertSessionHas('success');

        $this->assertSame('rejected', $claim->fresh()->status);
        $this->assertSame('open', $item->fresh()->status);
    }

    public function test_cannot_approve_an_already_decided_claim(): void
    {
        $item = $this->item();
        $claim = $this->claim($item, $this->other, 'rejected');

        $this->actingAs($this->poster)->patch("/claims/{$claim->id}/approve")->assertSessionHas('error');
        $this->assertSame('rejected', $claim->fresh()->status);
        $this->assertSame('open', $item->fresh()->status);
    }

    public function test_approved_contact_is_shown_only_after_approval(): void
    {
        $item = $this->item();
        $claim = $this->claim($item, $this->other);

        $this->actingAs($this->poster)->get("/items/{$item->id}")->assertDontSee('WA 0812');
        $this->actingAs($this->poster)->patch("/claims/{$claim->id}/approve");
        $this->actingAs($this->poster)->get("/items/{$item->id}")->assertSee('WA 0812');
    }

    // ---- F9: mark returned -------------------------------------------------------------

    public function test_cannot_mark_open_item_as_returned_or_someone_elses(): void
    {
        $open = $this->item();
        $this->actingAs($this->poster)->patch("/items/{$open->id}/returned")->assertSessionHas('error');
        $this->assertSame('open', $open->fresh()->status);

        $claimed = $this->item(['status' => 'claimed']);
        $this->actingAs($this->other)->patch("/items/{$claimed->id}/returned")->assertForbidden();
        $this->assertSame('claimed', $claimed->fresh()->status);
    }

    // ---- F10: categories ---------------------------------------------------------------

    public function test_category_with_items_cannot_be_deleted_but_empty_one_can(): void
    {
        $this->item();
        $empty = Category::create(['name' => 'Umbrellas', 'slug' => 'umbrellas']);

        $this->actingAs($this->admin)->delete("/admin/categories/{$this->category->id}")->assertSessionHas('error');
        $this->assertModelExists($this->category);

        $this->actingAs($this->admin)->delete("/admin/categories/{$empty->id}")->assertSessionHas('success');
        $this->assertModelMissing($empty);
    }

    public function test_category_names_must_be_unique_and_slugs_never_collide(): void
    {
        $this->actingAs($this->admin)->post('/admin/categories', ['name' => 'Electronics'])->assertSessionHasErrors('name');

        $this->actingAs($this->admin)->post('/admin/categories', ['name' => 'C']);
        $this->actingAs($this->admin)->post('/admin/categories', ['name' => 'C++'])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('categories', ['name' => 'C++', 'slug' => 'c-2']);
    }

    public function test_admin_can_rename_category(): void
    {
        $this->actingAs($this->admin)->put("/admin/categories/{$this->category->id}", ['name' => 'Gadgets'])->assertRedirect();
        $this->assertDatabaseHas('categories', ['id' => $this->category->id, 'name' => 'Gadgets', 'slug' => 'gadgets']);
    }

    // ---- F11: dashboard ----------------------------------------------------------------

    public function test_dashboard_shows_claims_waiting_for_my_review(): void
    {
        $item = $this->item(['title' => 'Item with a pending claim']);
        $this->claim($item, $this->other);

        $this->actingAs($this->poster)->get('/dashboard')
            ->assertOk()
            ->assertSee('Needs your review')
            ->assertSee('Other Person')
            ->assertViewHas('stats', fn ($s) => $s['to_review'] === 1);

        // The claimant does not get it in their review queue.
        $this->actingAs($this->other)->get('/dashboard')
            ->assertViewHas('stats', fn ($s) => $s['to_review'] === 0 && $s['my_pending_claims'] === 1);
    }

    // ---- Seeder ------------------------------------------------------------------------

    public function test_demo_seeder_runs_and_covers_every_status(): void
    {
        $this->seed();

        foreach (['open', 'claimed', 'returned'] as $status) {
            $this->assertTrue(Item::where('status', $status)->exists(), "no $status item");
        }
        foreach (['pending', 'approved', 'rejected'] as $status) {
            $this->assertTrue(Claim::where('status', $status)->exists(), "no $status claim");
        }
        $this->assertTrue(Category::doesntHave('items')->exists(), 'no empty category for the delete test');
        $this->get('/')->assertOk();
    }
}
