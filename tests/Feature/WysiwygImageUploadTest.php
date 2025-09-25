<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Media;
use App\Models\Manage\Role;
use App\Models\Manage\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class WysiwygImageUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create necessary directories
        Storage::fake('public');
    }

    public function test_wysiwyg_image_upload_success()
    {
        // Create user with upload permissions
        /** @var \App\Models\User $user */
        $user = User::factory()->createOne();

        $role = Role::firstOrCreate(['name' => 'administrator'], ['guard_name' => 'web']);
        $permission = Permission::firstOrCreate(['name' => 'upload files'], ['guard_name' => 'web']);
        $role->givePermissionTo($permission);
        DB::table('role_user')->insert([
            'role_id' => $role->getKey(),
            'user_id' => $user->getKey(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->be($user);

        // Create a fake image file
        $file = UploadedFile::fake()->image('test-image.jpg', 800, 600)->size(100);

        $response = $this->postJson(route('wysiwyg.upload'), [
            'image' => $file,
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure([
                'success',
                'url',
                'filename',
                'id'
            ]);

        // Check that file was stored with original name in date-based directory
        $datePath = now()->format('Y') . '/' . now()->format('m') . '/' . now()->format('d');
        $expectedPath = 'uploads/' . $datePath . '/test-image.jpg';
        $this->assertTrue(Storage::disk('public')->exists($expectedPath));

        // Check that Media record was created
        $this->assertDatabaseHas('media', [
            'filename' => 'test-image.jpg',
            'path' => $expectedPath,
            'user_id' => $user->id,
        ]);

        $media = Media::where('filename', 'test-image.jpg')->first();
        $this->assertNotNull($media);
        $this->assertEquals('image/jpeg', $media->mime_type);
    }

    public function test_wysiwyg_upload_handles_duplicate_filenames()
    {
        // Create user with upload permissions
        /** @var \App\Models\User $user */
        $user = User::factory()->createOne();

        $role = Role::firstOrCreate(['name' => 'administrator'], ['guard_name' => 'web']);
        $permission = Permission::firstOrCreate(['name' => 'upload files'], ['guard_name' => 'web']);
        $role->givePermissionTo($permission);
        DB::table('role_user')->insert([
            'role_id' => $role->getKey(),
            'user_id' => $user->getKey(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->be($user);

        // Create date-based path
        $datePath = now()->format('Y') . '/' . now()->format('m') . '/' . now()->format('d');
        $existingFile = 'uploads/' . $datePath . '/duplicate.jpg';

        // Create an existing file with the same name
        Storage::disk('public')->put($existingFile, 'existing content');

        // Create a new file with same name
        $file = UploadedFile::fake()->image('duplicate.jpg', 800, 600)->size(100);

        $response = $this->postJson(route('wysiwyg.upload'), [
            'image' => $file,
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $responseData = $response->json();

        // Should have a timestamped filename
        $this->assertStringContainsString('duplicate_', $responseData['filename']);
        $this->assertStringEndsWith('.jpg', $responseData['filename']);

        // Both files should exist
        $this->assertTrue(Storage::disk('public')->exists($existingFile));
        $this->assertTrue(Storage::disk('public')->exists('uploads/' . $datePath . '/' . $responseData['filename']));
    }

    public function test_wysiwyg_upload_requires_authentication()
    {
        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->post(route('wysiwyg.upload'), [
            'image' => $file,
        ]);

        $response->assertRedirect('/login');
    }

    public function test_wysiwyg_upload_requires_permission()
    {
        // Create user without upload permission
        /** @var \App\Models\User $user */
        $user = User::factory()->createOne();
        $this->be($user);

        $file = UploadedFile::fake()->image('test.jpg');

        $response = $this->post(route('wysiwyg.upload'), [
            'image' => $file,
        ]);

        $response->assertStatus(403);
    }

    public function test_wysiwyg_upload_validates_file_type()
    {
        // Create user with upload permissions
        /** @var \App\Models\User $user */
        $user = User::factory()->createOne();

        $role = Role::firstOrCreate(['name' => 'administrator'], ['guard_name' => 'web']);
        $permission = Permission::firstOrCreate(['name' => 'upload files'], ['guard_name' => 'web']);
        $role->givePermissionTo($permission);
        DB::table('role_user')->insert([
            'role_id' => $role->getKey(),
            'user_id' => $user->getKey(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->be($user);

        // Try to upload a non-image file
        $file = UploadedFile::fake()->create('document.txt', 100, 'text/plain');

        $response = $this->postJson(route('wysiwyg.upload'), [
            'image' => $file,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('image');
    }
}
