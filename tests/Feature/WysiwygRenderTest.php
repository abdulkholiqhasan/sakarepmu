<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Manage\Role;
use App\Models\Manage\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class WysiwygRenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_post_page_contains_wysiwyg_assets_and_editor()
    {
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

        // Quill style link should be present via stack
        $response->assertSee('cdn.quilljs.com/1.3.6/quill.snow.css', false);

        // Editor div id should be rendered
        $response->assertSee('id="content-editor"', false);
    }
}
