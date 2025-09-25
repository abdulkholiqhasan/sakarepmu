<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Blog\Post;
use App\Models\Manage\Role;
use App\Models\Manage\Permission;
use Illuminate\Support\Facades\DB;

class WysiwygColorTest extends TestCase
{
    use RefreshDatabase;

    private function createUserWithPostPermissions(): User
    {
        /** @var User $user */
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

        return $user;
    }

    public function test_wysiwyg_component_includes_color_toolbar_options(): void
    {
        $user = $this->createUserWithPostPermissions();

        $response = $this->actingAs($user)->get('/manage/posts/posts/create');

        $response->assertStatus(200);

        // Check that color toolbar options are present
        $response->assertSeeInOrder([
            '{ \'color\': [] }',
            '{ \'background\': [] }'
        ], false);
    }

    public function test_wysiwyg_component_includes_color_picker_styles(): void
    {
        $user = $this->createUserWithPostPermissions();

        $response = $this->actingAs($user)->get('/manage/posts/posts/create');

        $response->assertStatus(200);

        // Check that color picker CSS is included
        $response->assertSeeText('Color picker styles - light mode');
        $response->assertSeeText('.ql-color-picker');
        $response->assertSeeText('.ql-background-picker');
        $response->assertSeeText('Dark mode formula adjustments');
    }

    public function test_post_content_with_colored_text_is_saved_correctly(): void
    {
        $user = $this->createUserWithPostPermissions();

        $coloredContent = '<p><span style="color: rgb(230, 0, 0);">Red text</span> and <span style="background-color: rgb(255, 255, 0);">yellow background</span></p>';

        $response = $this->actingAs($user)->post('/manage/posts/posts', [
            'title' => 'Test Post with Colors',
            'slug' => 'test-post-colors',
            'content' => $coloredContent,
            'published_at' => now()->format('Y-m-d\TH:i'),
            'status' => 'published'
        ]);

        $response->assertRedirect('/manage/posts/posts');

        $post = Post::where('slug', 'test-post-colors')->first();
        $this->assertNotNull($post);
        $this->assertStringContainsString('color: rgb(230, 0, 0)', $post->content);
        $this->assertStringContainsString('background-color: rgb(255, 255, 0)', $post->content);
    }

    public function test_wysiwyg_color_javascript_enhancement_is_present(): void
    {
        $user = $this->createUserWithPostPermissions();

        $response = $this->actingAs($user)->get('/manage/posts/posts/create');

        $response->assertStatus(200);

        // Check that color enhancement JavaScript is present
        $response->assertSeeText('Color picker enhancement');
        $response->assertSeeText('Text Color');
        $response->assertSeeText('Background Color');
    }
}
