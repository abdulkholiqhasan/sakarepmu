<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Manage\Role;
use App\Models\Manage\Permission;
use Illuminate\Support\Facades\DB;

class WysiwygVisualDuplicationTest extends TestCase
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

    public function test_no_duplicate_formula_buttons_in_html(): void
    {
        $user = $this->createUserWithPostPermissions();
        $response = $this->actingAs($user)->get('/manage/posts/posts/create');
        $response->assertStatus(200);

        $content = $response->getContent();

        // Debug: Check what's actually in the toolbar
        $this->assertStringContainsString('formula', $content);

        // Look for any patterns that might create duplicate buttons
        preg_match_all('/class="[^"]*ql-[^"]*formula[^"]*"/', $content, $classMatches);
        preg_match_all('/\bformula\b/', $content, $wordMatches);

        // Count button-like elements that might contain formula
        preg_match_all('/<button[^>]*formula[^>]*>/', $content, $buttonMatches);

        // Debug output for analysis
        $this->addToAssertionCount(1); // Prevent risky test warning

        // Should not have multiple button elements with formula
        $this->assertLessThanOrEqual(
            1,
            count($buttonMatches[0]),
            "Should not have multiple button elements containing 'formula'"
        );
    }

    public function test_check_quill_default_modules(): void
    {
        $user = $this->createUserWithPostPermissions();
        $response = $this->actingAs($user)->get('/manage/posts/posts/create');
        $response->assertStatus(200);

        $content = $response->getContent();

        // Check if there are any conflicting modules or handlers
        $this->assertStringNotContainsString('modules: [', $content);
        $this->assertStringNotContainsString('Quill.import(\'modules/formula\')', $content);

        // Make sure we're not accidentally including formula twice
        $modulesCount = substr_count($content, 'modules: {');
        $this->assertEquals(1, $modulesCount, "Should have exactly one modules configuration");
    }

    public function test_toolbar_structure_analysis(): void
    {
        $user = $this->createUserWithPostPermissions();
        $response = $this->actingAs($user)->get('/manage/posts/posts/create');
        $response->assertStatus(200);

        $content = $response->getContent();

        // Extract the toolbar configuration
        preg_match('/toolbarOptions\s*=\s*\[(.*?)\];/s', $content, $matches);

        if (!empty($matches[1])) {
            $toolbarConfig = $matches[1];

            // Count how many times 'formula' appears in toolbar config
            $formulaCount = substr_count($toolbarConfig, "'formula'");
            $this->assertEquals(
                1,
                $formulaCount,
                "Formula should appear exactly once in toolbar configuration. Found: {$formulaCount}"
            );

            // Check the exact position and context
            $this->assertStringContainsString("'link', 'image', 'formula', 'clean'", $toolbarConfig);
        } else {
            $this->fail("Could not find toolbar configuration in response");
        }
    }

    public function test_no_conflicting_css_selectors(): void
    {
        $user = $this->createUserWithPostPermissions();
        $response = $this->actingAs($user)->get('/manage/posts/posts/create');
        $response->assertStatus(200);

        $content = $response->getContent();

        // Check for CSS that might create duplicate visual elements
        $formulaCssCount = substr_count($content, '.ql-formula');
        $beforePseudoCount = substr_count($content, '.ql-formula:before');

        // Should have CSS for styling but not duplicated
        $this->assertGreaterThan(0, $formulaCssCount, "Should have formula CSS");
        $this->assertEquals(1, $beforePseudoCount, "Should have exactly one :before pseudo element");

        // Check for cleanup CSS (these should be present as they hide duplicates)
        $this->assertStringContainsString('.ql-fx', $content, "Should have .ql-fx selector for hiding duplicates");
        $this->assertStringContainsString('display: none !important', $content, "Should hide duplicate buttons");

        // Check that cleanup doesn't interfere with our main button
        $this->assertStringContainsString('.ql-formula', $content, "Should have .ql-formula selector");
        $this->assertStringContainsString('display: inline-block !important', $content, "Should ensure formula button visibility");
    }
}
