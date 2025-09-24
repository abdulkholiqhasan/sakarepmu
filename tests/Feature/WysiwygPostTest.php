<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Manage\Role;
use App\Models\Manage\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class WysiwygPostTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_store_saves_html_content()
    {
        // create a user and give necessary permission
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

        $payload = [
            'title' => 'Test WYSIWYG Post',
            'content' => '<h1>Heading</h1><p><strong>Bold text</strong> and <em>italic</em></p>',
            // slug will be generated if not provided
        ];

        $response = $this->post(route('posts.store'), $payload);

        $response->assertRedirect(route('posts.index'));

        $this->assertDatabaseHas('posts', [
            'title' => 'Test WYSIWYG Post',
            'content' => '<h1>Heading</h1><p><strong>Bold text</strong> and <em>italic</em></p>',
        ]);
    }
}
