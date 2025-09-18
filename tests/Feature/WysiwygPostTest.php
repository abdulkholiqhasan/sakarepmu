<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WysiwygPostTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_store_saves_html_content()
    {
        // create a user and act as them
        /** @var \App\Models\User $user */
        $user = User::factory()->createOne();

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
