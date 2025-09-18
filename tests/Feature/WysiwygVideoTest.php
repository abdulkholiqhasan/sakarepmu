<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

it('saves post content containing video iframe html', function () {
    // create a user and authenticate
    $user = User::factory()->create();
    $this->actingAs($user);

    // minimal post data - adjust keys to match your posts store route expectations
    $payload = [
        'title' => 'Video post',
        'slug' => 'video-post',
        'content' => '<p>Intro</p><div class="ql-video-embed"><iframe src="https://www.youtube.com/embed/VznDxcjUkhM" frameborder="0" allowfullscreen></iframe></div>'
    ];

    // POST to store route - change route name if different in your app
    $response = $this->post('/manage/posts/posts', $payload);
    $response->assertRedirect();

    $this->assertDatabaseHas('posts', [
        'title' => 'Video post',
        // Ensure content contains the iframe src
        // Use like query on content by retrieving the record and asserting string contains
    ]);

    $post = DB::table('posts')->where('slug', 'video-post')->first();
    expect($post)->not->toBeNull();
    expect($post->content)->toContain('youtube.com/embed/VznDxcjUkhM');
});
