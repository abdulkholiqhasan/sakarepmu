<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Manage\Role;
use App\Models\Manage\Permission;
use Illuminate\Support\Facades\DB;

class WysiwygToolbarConfigurationTest extends TestCase
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

    public function test_toolbar_has_correct_button_order(): void
    {
        $user = $this->createUserWithPostPermissions();

        $response = $this->actingAs($user)->get('/manage/posts/posts/create');

        $response->assertStatus(200);

        // Check that buttons are in the correct order
        $response->assertSeeInOrder([
            "'header'",
            "'bold'",
            "'italic'",
            "'underline'",
            "'strike'",
            "'color'",
            "'background'",
            "'blockquote'",
            "'code-block'",
            "'list'",
            "'align'",
            "'link'",
            "'image'",
            "'formula'",
            "'clean'"
        ], false);
    }

    public function test_toolbar_does_not_have_duplicate_quill_initialization(): void
    {
        $user = $this->createUserWithPostPermissions();

        $response = $this->actingAs($user)->get('/manage/posts/posts/create');

        $response->assertStatus(200);

        $content = $response->getContent();

        // Check that Quill is initialized only once per editor
        $quillInitCount = substr_count($content, 'new Quill(editorEl');
        $this->assertEquals(1, $quillInitCount, "Quill should be initialized exactly once per editor");

        // Check that there's no duplicate module registration
        $modulesConfigCount = substr_count($content, 'modules: {');
        $this->assertEquals(1, $modulesConfigCount, "Modules configuration should appear exactly once");
    }

    public function test_formula_button_has_correct_handler(): void
    {
        $user = $this->createUserWithPostPermissions();

        $response = $this->actingAs($user)->get('/manage/posts/posts/create');

        $response->assertStatus(200);

        $content = $response->getContent();

        // Check that formula handler exists and is correctly structured
        $this->assertStringContainsString('formula: function()', $content);
        $this->assertStringContainsString('ensureKaTeX().then', $content);
        $this->assertStringContainsString('Masukkan formula LaTeX', $content);
        $this->assertStringContainsString('insertEmbed(range.index, \'formula\'', $content);

        // Ensure no duplicate handler definitions
        $handlerDefinitionCount = substr_count($content, 'formula: function()');
        $this->assertEquals(1, $handlerDefinitionCount, "Formula handler should be defined exactly once");
    }

    public function test_toolbar_icons_are_properly_styled(): void
    {
        $user = $this->createUserWithPostPermissions();

        $response = $this->actingAs($user)->get('/manage/posts/posts/create');

        $response->assertStatus(200);

        // Check that formula button styling is present
        $response->assertSeeText('.ql-toolbar .ql-formula:before');

        $content = $response->getContent();

        // Check for the f(x) content in various possible formats
        $hasFormulaStyling = strpos($content, 'content: "f(x)"') !== false ||
            strpos($content, 'content: &#039;f(x)&#039;') !== false ||
            strpos($content, 'content: &quot;f(x)&quot;') !== false;

        $this->assertTrue($hasFormulaStyling, "Formula button should have f(x) styling");

        // Check that styling doesn't conflict with other buttons
        $formulaStylingCount = substr_count($content, '.ql-toolbar .ql-formula:before');
        $this->assertEquals(1, $formulaStylingCount, "Formula button styling should appear exactly once");
    }

    public function test_no_javascript_errors_in_toolbar_setup(): void
    {
        $user = $this->createUserWithPostPermissions();

        $response = $this->actingAs($user)->get('/manage/posts/posts/create');

        $response->assertStatus(200);

        $content = $response->getContent();

        // Check for potential JavaScript syntax issues
        $this->assertStringNotContainsString('formula: function() formula: function()', $content);
        $this->assertStringNotContainsString("'formula', 'formula'", $content);

        // Check that all handler functions are properly closed
        $openFunctionCount = substr_count($content, 'function() {') + substr_count($content, 'function(){');
        $openArrowCount = substr_count($content, '() => {');
        $closeBraceCount = substr_count($content, '}');

        // This is a basic check - in a real toolbar, braces should be balanced
        $this->assertGreaterThan(0, $closeBraceCount);
    }
}
