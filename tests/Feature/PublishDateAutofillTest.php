<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Manage\Role;
use App\Models\Manage\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PublishDateAutofillTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Set a fixed time for consistent testing
        Carbon::setTestNow('2025-09-25 15:30:00');
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow(); // Reset
        parent::tearDown();
    }

    public function test_post_create_form_has_autofilled_publish_date()
    {
        // Create user with necessary permissions
        /** @var \App\Models\User $user */
        $user = User::factory()->createOne();

        $role = Role::firstOrCreate(['name' => 'administrator'], ['guard_name' => 'web']);
        $permission = Permission::firstOrCreate(['name' => 'create posts'], ['guard_name' => 'web']);
        $role->givePermissionTo($permission);
        DB::table('role_user')->insert([
            'role_id' => $role->getKey(),
            'user_id' => $user->getKey(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->be($user);

        $response = $this->get(route('posts.create'));
        $response->assertStatus(200);

        // Check that the published_at field has the current datetime pre-filled
        $expectedDateTime = now()->format('Y-m-d\TH:i');
        $response->assertSee('value="' . $expectedDateTime . '"', false);

        // Ensure it's still an editable field
        $response->assertSee('name="published_at"', false);
        $response->assertSee('type="datetime-local"', false);
        $response->assertSee('id="published_at"', false);

        // Check that reset to now button is present
        $response->assertSee('Reset to now', false);
        $response->assertSee('onclick="resetPublishDateToNow()"', false);
    }

    public function test_page_create_form_has_autofilled_publish_date()
    {
        // Create user with necessary permissions
        /** @var \App\Models\User $user */
        $user = User::factory()->createOne();

        $role = Role::firstOrCreate(['name' => 'administrator'], ['guard_name' => 'web']);
        $permission = Permission::firstOrCreate(['name' => 'manage pages'], ['guard_name' => 'web']);
        $role->givePermissionTo($permission);
        DB::table('role_user')->insert([
            'role_id' => $role->getKey(),
            'user_id' => $user->getKey(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->be($user);

        $response = $this->get(route('pages.create'));
        $response->assertStatus(200);

        // Check that the published_at field has the current datetime pre-filled
        $expectedDateTime = now()->format('Y-m-d\TH:i');
        $response->assertSee('value="' . $expectedDateTime . '"', false);

        // Ensure it's still an editable field
        $response->assertSee('name="published_at"', false);
        $response->assertSee('type="datetime-local"', false);
        $response->assertSee('id="published_at"', false);

        // Check that reset to now button is present
        $response->assertSee('Reset to now', false);
        $response->assertSee('onclick="resetPublishDateToNow()"', false);
    }

    public function test_post_can_be_created_with_custom_publish_date()
    {
        // Create user with necessary permissions
        /** @var \App\Models\User $user */
        $user = User::factory()->createOne();

        $role = Role::firstOrCreate(['name' => 'administrator'], ['guard_name' => 'web']);
        $permission = Permission::firstOrCreate(['name' => 'create posts'], ['guard_name' => 'web']);
        $role->givePermissionTo($permission);
        DB::table('role_user')->insert([
            'role_id' => $role->getKey(),
            'user_id' => $user->getKey(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->be($user);

        // Custom publish date different from current time
        $customPublishDate = '2025-12-25T10:30';

        $payload = [
            'title' => 'Test Post with Custom Publish Date',
            'content' => '<p>This is test content</p>',
            'published_at' => $customPublishDate,
            'action' => 'publish'
        ];

        $response = $this->post(route('posts.store'), $payload);
        $response->assertRedirect(route('posts.index'));

        // Check that the post was created with the custom publish date
        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post with Custom Publish Date',
            'published_at' => '2025-12-25 10:30:00',
        ]);
    }

    public function test_page_can_be_created_with_custom_publish_date()
    {
        // Create user with necessary permissions
        /** @var \App\Models\User $user */
        $user = User::factory()->createOne();

        $role = Role::firstOrCreate(['name' => 'administrator'], ['guard_name' => 'web']);
        $permission = Permission::firstOrCreate(['name' => 'manage pages'], ['guard_name' => 'web']);
        $role->givePermissionTo($permission);
        DB::table('role_user')->insert([
            'role_id' => $role->getKey(),
            'user_id' => $user->getKey(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->be($user);

        // Custom publish date different from current time
        $customPublishDate = '2025-12-25T10:30';

        $payload = [
            'title' => 'Test Page with Custom Publish Date',
            'content' => '<p>This is test content for a page</p>',
            'published_at' => $customPublishDate,
            'action' => 'publish'
        ];

        $response = $this->post(route('pages.store'), $payload);
        $response->assertRedirect(route('pages.index'));

        // Check that the page was created with the custom publish date
        $this->assertDatabaseHas('pages', [
            'title' => 'Test Page with Custom Publish Date',
            'published_at' => '2025-12-25 10:30:00',
        ]);
    }

    public function test_post_validation_errors_preserve_custom_publish_date()
    {
        // Create user with necessary permissions
        /** @var \App\Models\User $user */
        $user = User::factory()->createOne();

        $role = Role::firstOrCreate(['name' => 'administrator'], ['guard_name' => 'web']);
        $permission = Permission::firstOrCreate(['name' => 'create posts'], ['guard_name' => 'web']);
        $role->givePermissionTo($permission);
        DB::table('role_user')->insert([
            'role_id' => $role->getKey(),
            'user_id' => $user->getKey(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->be($user);

        $customPublishDate = '2025-12-25T10:30';

        // Submit form with missing title to trigger validation error
        $payload = [
            'title' => '', // Empty title should cause validation error
            'content' => '<p>This is test content</p>',
            'published_at' => $customPublishDate,
            'action' => 'publish'
        ];

        $response = $this->post(route('posts.store'), $payload);
        $response->assertSessionHasErrors('title');

        // Follow redirect to see the form with old input
        $response = $this->get(route('posts.create'));

        // The custom publish date should be preserved in the form
        $response->assertSee('value="' . $customPublishDate . '"', false);
    }

    public function test_default_publish_date_uses_current_server_time()
    {
        // Create user with necessary permissions
        /** @var \App\Models\User $user */
        $user = User::factory()->createOne();

        $role = Role::firstOrCreate(['name' => 'administrator'], ['guard_name' => 'web']);
        $permission = Permission::firstOrCreate(['name' => 'create posts'], ['guard_name' => 'web']);
        $role->givePermissionTo($permission);
        DB::table('role_user')->insert([
            'role_id' => $role->getKey(),
            'user_id' => $user->getKey(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->be($user);

        $response = $this->get(route('posts.create'));

        // The datetime should match our test time
        $expectedDateTime = Carbon::now()->format('Y-m-d\TH:i');
        $this->assertEquals('2025-09-25T15:30', $expectedDateTime);

        $response->assertSee('value="' . $expectedDateTime . '"', false);
    }
}
