<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Manage\Role;
use App\Models\Manage\Permission;
use Illuminate\Support\Facades\DB;

class WysiwygBuiltinFormulaHandlingTest extends TestCase
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

    public function test_builtin_formula_svg_is_hidden(): void
    {
        $user = $this->createUserWithPostPermissions();
        $response = $this->actingAs($user)->get('/manage/posts/posts/create');
        $response->assertStatus(200);

        $content = $response->getContent();

        // Check that CSS rule to hide SVG is present
        $this->assertStringContainsString('.ql-toolbar .ql-formula svg', $content);
        $this->assertStringContainsString('display: none !important', $content);
        $this->assertStringContainsString('Hide the built-in Quill formula button SVG', $content);
    }

    public function test_custom_formula_text_styling_is_present(): void
    {
        $user = $this->createUserWithPostPermissions();
        $response = $this->actingAs($user)->get('/manage/posts/posts/create');
        $response->assertStatus(200);

        $content = $response->getContent();

        // Check that custom f(x) styling is present
        $this->assertStringContainsString('.ql-toolbar .ql-formula:after', $content);
        $hasFormulaStyling = strpos($content, 'content: "f(x)"') !== false;
        $this->assertTrue($hasFormulaStyling, "Should have f(x) content in :after pseudo element");
    }

    public function test_formula_button_configuration_is_correct(): void
    {
        $user = $this->createUserWithPostPermissions();
        $response = $this->actingAs($user)->get('/manage/posts/posts/create');
        $response->assertStatus(200);

        $content = $response->getContent();

        // Extract toolbar configuration
        preg_match('/toolbarOptions\s*=\s*\[(.*?)\];/s', $content, $matches);
        $this->assertNotEmpty($matches[1], "Should find toolbar configuration");

        $toolbarConfig = $matches[1];

        // Should still have formula in configuration
        $this->assertStringContainsString("'formula'", $toolbarConfig);

        // Should have custom handler
        $this->assertStringContainsString('formula: function()', $content);
        $this->assertStringContainsString('ensureKaTeX().then', $content);
    }

    public function test_no_conflicts_between_builtin_and_custom(): void
    {
        $user = $this->createUserWithPostPermissions();
        $response = $this->actingAs($user)->get('/manage/posts/posts/create');
        $response->assertStatus(200);

        $content = $response->getContent();

        // Should have both :before and :after selectors for comprehensive coverage
        $beforeCount = substr_count($content, '.ql-toolbar .ql-formula:before');
        $afterCount = substr_count($content, '.ql-toolbar .ql-formula:after');

        $this->assertEquals(1, $beforeCount, "Should have exactly one :before selector");
        $this->assertEquals(1, $afterCount, "Should have exactly one :after selector");

        // Should have SVG hiding rule
        $svgHideCount = substr_count($content, '.ql-toolbar .ql-formula svg');
        $this->assertEquals(1, $svgHideCount, "Should have exactly one SVG hiding rule");
    }

    public function test_formula_functionality_remains_intact(): void
    {
        $user = $this->createUserWithPostPermissions();
        $response = $this->actingAs($user)->get('/manage/posts/posts/create');
        $response->assertStatus(200);

        $content = $response->getContent();

        // Verify that all formula functionality is still present
        $this->assertStringContainsString('FormulaBlot', $content);
        $this->assertStringContainsString('katex.render', $content);
        $this->assertStringContainsString('insertEmbed(range.index, \'formula\'', $content);
        $this->assertStringContainsString('Masukkan formula LaTeX', $content);

        // Verify KaTeX integration
        $this->assertStringContainsString('ensureKaTeX', $content);
        $this->assertStringContainsString('katex.min.js', $content);
        $this->assertStringContainsString('katex.min.css', $content);
    }
}
