<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Blog\Post;
use App\Models\Manage\Role;
use App\Models\Manage\Permission;
use Illuminate\Support\Facades\DB;

class WysiwygFormulaTest extends TestCase
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

    public function test_wysiwyg_component_includes_formula_toolbar_option(): void
    {
        $user = $this->createUserWithPostPermissions();

        $response = $this->actingAs($user)->get('/manage/posts/posts/create');

        $response->assertStatus(200);

        // Check that formula toolbar option is present
        $response->assertSee('formula');
    }

    public function test_wysiwyg_component_includes_katex_css(): void
    {
        $user = $this->createUserWithPostPermissions();

        $response = $this->actingAs($user)->get('/manage/posts/posts/create');

        $response->assertStatus(200);

        // Check that KaTeX CSS is included
        $response->assertSee('https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.css');
    }

    public function test_wysiwyg_component_includes_formula_styles(): void
    {
        $user = $this->createUserWithPostPermissions();

        $response = $this->actingAs($user)->get('/manage/posts/posts/create');

        $response->assertStatus(200);

        // Check that formula-specific CSS is included
        $response->assertSeeText('.ql-formula');
        $response->assertSeeText('Formula/Math styles');
    }

    public function test_wysiwyg_includes_katex_javascript(): void
    {
        $user = $this->createUserWithPostPermissions();

        $response = $this->actingAs($user)->get('/manage/posts/posts/create');

        $response->assertStatus(200);

        // Check that KaTeX JavaScript loading is present
        $response->assertSeeText('ensureKaTeX');
        $response->assertSee('https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.js');
    }

    public function test_wysiwyg_includes_formula_handler(): void
    {
        $user = $this->createUserWithPostPermissions();

        $response = $this->actingAs($user)->get('/manage/posts/posts/create');

        $response->assertStatus(200);

        // Check that formula handler is present
        $response->assertSeeText('formula: function()');
        $response->assertSeeText('Masukkan formula LaTeX');
    }

    public function test_wysiwyg_includes_formula_blot_registration(): void
    {
        $user = $this->createUserWithPostPermissions();

        $response = $this->actingAs($user)->get('/manage/posts/posts/create');

        $response->assertStatus(200);

        // Check that FormulaBlot registration is present
        $response->assertSeeText('FormulaBlot');
        $response->assertSeeText('__quill_formulaBlot_registered');
    }

    public function test_post_content_with_formula_can_be_saved(): void
    {
        $user = $this->createUserWithPostPermissions();

        $formulaContent = '<p>Einstein\'s famous equation: <span class="ql-formula" data-formula="E = mc^2">E = mc²</span></p>';

        $response = $this->actingAs($user)->post('/manage/posts/posts', [
            'title' => 'Test Post with Formula',
            'slug' => 'test-post-formula',
            'content' => $formulaContent,
            'published_at' => now()->format('Y-m-d\TH:i'),
            'status' => 'published'
        ]);

        $response->assertRedirect('/manage/posts/posts');

        $post = Post::where('slug', 'test-post-formula')->first();
        $this->assertNotNull($post);
        $this->assertStringContainsString('ql-formula', $post->content);
        $this->assertStringContainsString('data-formula="E = mc^2"', $post->content);
    }
}
