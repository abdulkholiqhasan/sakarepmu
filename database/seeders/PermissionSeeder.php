<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Manage\Permission;
use App\Models\Manage\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Seed permissions based on application features and assign sensible defaults to roles.
     */
    public function run(): void
    {
        // Define permissions per feature
        $featurePermissions = [
            // User & access management
            'manage users' => ['administrator'],
            'manage roles' => ['administrator'],
            'manage permissions' => ['administrator'],

            // Posts and pages
            'create posts' => ['administrator', 'editor', 'author'],
            'edit posts' => ['administrator', 'editor', 'author'],
            'publish posts' => ['administrator', 'editor'],
            'delete posts' => ['administrator', 'editor'],
            'manage pages' => ['administrator', 'editor'],

            // Taxonomies
            'create categories' => ['administrator', 'editor'],
            'edit categories' => ['administrator', 'editor'],
            'delete categories' => ['administrator', 'editor'],
            'create tags' => ['administrator', 'editor', 'author'],
            'edit tags' => ['administrator', 'editor'],
            'delete tags' => ['administrator', 'editor'],

            // Media
            'upload files' => ['administrator', 'editor', 'author'],
            'delete media' => ['administrator', 'editor'],

            // Settings
            'manage settings' => ['administrator'],
        ];

        foreach ($featurePermissions as $permName => $roles) {
            $permission = Permission::firstOrCreate(['name' => $permName], ['guard_name' => 'web']);

            // attach permission to roles (use givePermissionTo so pivot id is set)
            foreach ($roles as $roleName) {
                $role = Role::firstOrCreate(['name' => $roleName], ['guard_name' => 'web']);
                $role->givePermissionTo($permission);
            }
        }
    }
}
