<?php

use App\Models\User;
use App\Models\Manage\Role;
use App\Models\Manage\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

it('saves post content containing arbitrary html embed', function () {
    $user = User::factory()->create();

    $role = Role::firstOrCreate(['name' => 'administrator'], ['guard_name' => 'web']);
    $permission = Permission::firstOrCreate(['name' => 'create posts'], ['guard_name' => 'web']);
    $role->givePermissionTo($permission);
    DB::table('role_user')->insert([
        'role_id' => $role->getKey(),
        'user_id' => $user->getKey(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->actingAs($user);

    $payload = [
        'title' => 'HTML embed post',
        'slug' => 'html-embed-post',
        'content' => '<p>Before</p><div class="ql-html-embed"><iframe src="https://player.vimeo.com/video/123456" frameborder="0" allowfullscreen></iframe></div>'
    ];

    $response = $this->post('/manage/posts/posts', $payload);
    $response->assertRedirect();

    $post = DB::table('posts')->where('slug', 'html-embed-post')->first();
    expect($post)->not->toBeNull();
    expect($post->content)->toContain('player.vimeo.com/video/123456');
});
