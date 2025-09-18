<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

it('saves post content containing arbitrary html embed', function () {
    $user = User::factory()->create();
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
