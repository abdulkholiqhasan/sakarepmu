<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Manage\Role;
use App\Models\Manage\Permission;
use Illuminate\Support\Facades\DB;

class WysiwygToolbarDuplicationTest extends TestCase
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

    public function test_toolbar_does_not_have_duplicate_formula_buttons(): void
    {
        $user = $this->createUserWithPostPermissions();

        $response = $this->actingAs($user)->get('/manage/posts/posts/create');

        $response->assertStatus(200);

        $content = $response->getContent();

        // Count occurrences of 'formula' in toolbar configuration specifically
        $toolbarConfigMatches = [];
        preg_match_all('/toolbarOptions\s*=\s*\[(.*?)\];/s', $content, $toolbarConfigMatches);

        $formulaInToolbarCount = 0;
        foreach ($toolbarConfigMatches[1] as $toolbarConfig) {
            $formulaInToolbarCount += substr_count($toolbarConfig, "'formula'");
        }

        // Should only appear once in toolbar configuration
        $this->assertEquals(1, $formulaInToolbarCount, "Formula should appear exactly once in toolbar configuration");

        // Check for duplicate toolbar button creation
        $toolbarOptionsCount = substr_count($content, 'toolbarOptions = [');
        $this->assertEquals(1, $toolbarOptionsCount, "toolbarOptions should be defined exactly once");
    }

    public function test_toolbar_formula_button_styling_is_unique(): void
    {
        $user = $this->createUserWithPostPermissions();

        $response = $this->actingAs($user)->get('/manage/posts/posts/create');

        $response->assertStatus(200);

        $content = $response->getContent();

        // Count CSS styling for formula button
        $formulaCssCount = substr_count($content, '.ql-toolbar .ql-formula:before');

        // Should only appear once
        $this->assertEquals(1, $formulaCssCount, "Formula button CSS should appear exactly once");

        // Check for correct f(x) content
        $this->assertStringContainsString('content: "f(x)"', $content);
    }

    public function test_no_duplicate_formula_handlers(): void
    {
        $user = $this->createUserWithPostPermissions();

        $response = $this->actingAs($user)->get('/manage/posts/posts/create');

        $response->assertStatus(200);

        $content = $response->getContent();

        // Count formula handler definitions
        $handlerCount = substr_count($content, 'formula: function()');

        // Should only appear once
        $this->assertEquals(1, $handlerCount, "Formula handler should be defined exactly once");
    }
}
