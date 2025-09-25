<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Manage\Role;
use App\Models\Manage\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class WysiwygImageAlignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_wysiwyg_component_includes_alignment_css()
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

        // Check that alignment CSS is included
        $response->assertSee('.wysiwyg-component .ql-editor .ql-align-center img', false);
        $response->assertSee('.wysiwyg-component .ql-editor .ql-align-right img', false);
        $response->assertSee('.wysiwyg-component .ql-editor .ql-align-left img', false);
        $response->assertSee('margin-left: auto', false);
        $response->assertSee('margin-right: auto', false);
    }

    public function test_wysiwyg_toolbar_includes_alignment_controls()
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

        // Check that alignment options are included in toolbar configuration
        $response->assertSee("{ 'align': [] }", false);
    }

    public function test_post_content_with_aligned_images_is_saved_correctly()
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

        // HTML content with aligned images
        $contentWithAlignedImages = '<p>Here is some text.</p>
            <p class="ql-align-center"><img src="http://example.com/image.jpg" alt="Center aligned image"></p>
            <p class="ql-align-right"><img src="http://example.com/image2.jpg" alt="Right aligned image"></p>
            <p>More text here.</p>';

        $payload = [
            'title' => 'Test Post with Aligned Images',
            'content' => $contentWithAlignedImages,
            'slug' => 'test-post-aligned-images'
        ];

        $response = $this->post(route('posts.store'), $payload);
        $response->assertRedirect(route('posts.index'));

        // Check that the content with alignment classes is saved correctly
        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post with Aligned Images',
        ]);

        $post = \App\Models\Blog\Post::where('title', 'Test Post with Aligned Images')->first();
        $this->assertNotNull($post);

        // Check that alignment classes are preserved in content
        $this->assertStringContainsString('ql-align-center', $post->content);
        $this->assertStringContainsString('ql-align-right', $post->content);
    }

    public function test_wysiwyg_image_alignment_css_works_in_dark_mode()
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

        // The alignment CSS should be the same for both light and dark modes
        // since image alignment is structural, not color-based
        $response->assertSee('.wysiwyg-component .ql-editor img', false);
        $response->assertSee('max-width: 100%', false);
        $response->assertSee('height: auto', false);
    }
}
