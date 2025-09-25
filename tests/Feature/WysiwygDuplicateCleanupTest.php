<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Manage\Role;
use App\Models\Manage\Permission;
use Illuminate\Support\Facades\DB;

class WysiwygDuplicateCleanupTest extends TestCase
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

    public function test_duplicate_cleanup_css_is_present(): void
    {
        $user = $this->createUserWithPostPermissions();
        $response = $this->actingAs($user)->get('/manage/posts/posts/create');
        $response->assertStatus(200);

        $content = $response->getContent();

        // Check that CSS rules to hide duplicates are present
        $this->assertStringContainsString('.ql-fx', $content);
        $this->assertStringContainsString('display: none !important', $content);
        $this->assertStringContainsString('Hide any potential duplicate formula buttons', $content);
    }

    public function test_duplicate_cleanup_javascript_is_present(): void
    {
        $user = $this->createUserWithPostPermissions();
        $response = $this->actingAs($user)->get('/manage/posts/posts/create');
        $response->assertStatus(200);

        $content = $response->getContent();

        // Check that JavaScript cleanup code is present
        $this->assertStringContainsString('Remove any duplicate formula buttons', $content);
        $this->assertStringContainsString('duplicateSelectors', $content);
        $this->assertStringContainsString('formulaButtons.length > 1', $content);
    }

    public function test_cleanup_targets_correct_selectors(): void
    {
        $user = $this->createUserWithPostPermissions();
        $response = $this->actingAs($user)->get('/manage/posts/posts/create');
        $response->assertStatus(200);

        $content = $response->getContent();

        // Check that cleanup targets the right selectors
        $this->assertStringContainsString("'.ql-fx'", $content);
        $this->assertStringContainsString("'.ql-function'", $content);
        $this->assertStringContainsString('button[title*="formula"]:not(.ql-formula)', $content);
        $this->assertStringContainsString('button[data-formula]:not(.ql-formula)', $content);
    }

    public function test_formula_button_visibility_ensured(): void
    {
        $user = $this->createUserWithPostPermissions();
        $response = $this->actingAs($user)->get('/manage/posts/posts/create');
        $response->assertStatus(200);

        $content = $response->getContent();

        // Check that our formula button is explicitly made visible
        $this->assertStringContainsString('display: inline-block !important', $content);
        $this->assertStringContainsString('Ensure our formula button is visible', $content);
    }

    public function test_cleanup_timing_is_appropriate(): void
    {
        $user = $this->createUserWithPostPermissions();
        $response = $this->actingAs($user)->get('/manage/posts/posts/create');
        $response->assertStatus(200);

        $content = $response->getContent();

        // Check that cleanup runs after color picker enhancement (100ms + 50ms delay)
        $colorEnhancementTiming = strpos($content, '}, 100);');
        $cleanupTiming = strpos($content, '}, 150);');

        $this->assertNotFalse($colorEnhancementTiming, 'Color enhancement timing should be present');
        $this->assertNotFalse($cleanupTiming, 'Cleanup timing should be present');
        $this->assertGreaterThan($colorEnhancementTiming, $cleanupTiming, 'Cleanup should run after color enhancement');
    }
}
