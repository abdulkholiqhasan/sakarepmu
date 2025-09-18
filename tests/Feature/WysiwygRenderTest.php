<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WysiwygRenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_post_page_contains_wysiwyg_assets_and_editor()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->createOne();
        $this->be($user);

        $response = $this->get(route('posts.create'));
        $response->assertStatus(200);

        // Quill style link should be present via stack
        $response->assertSee('cdn.quilljs.com/1.3.6/quill.snow.css', false);

        // Editor div id should be rendered
        $response->assertSee('id="content-editor"', false);
    }
}
