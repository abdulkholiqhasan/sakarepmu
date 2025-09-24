<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Manage\Role;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles first, then permissions
        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);

        // Ensure administrator role exists and create admin user
        $role = Role::firstOrCreate(['name' => 'administrator'], ['guard_name' => 'web']);

        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                // Hash the password explicitly to ensure compatibility
                'password' => Hash::make('password'),
            ]
        );
        // Ensure username follows role (administrator => 'admin')
        if (! $user->username || $user->username !== 'admin') {
            $user->username = 'admin';
            $user->save();
        }

        // Assign administrator role to the user (use safe insert when pivot schema doesn't have id)
        try {
            $columns = \Illuminate\Support\Facades\Schema::getColumnListing('role_user');
            if (is_array($columns) && in_array('id', $columns, true)) {
                $user->assignRole($role);
            } else {
                \Illuminate\Support\Facades\DB::table('role_user')->insert([
                    'role_id' => $role->getKey(),
                    'user_id' => $user->getKey(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            // Fallback to assignRole - model will try attaching and has its own fallbacks
            $user->assignRole($role);
        }

        // Create one user per role (editor, author, contributor, subscriber)
        $roleUsers = [
            'editor' => ['email' => 'editor@example.com', 'name' => 'Editor User'],
            'author' => ['email' => 'author@example.com', 'name' => 'Author User'],
            'contributor' => ['email' => 'contributor@example.com', 'name' => 'Contributor User'],
            'subscriber' => ['email' => 'subscriber@example.com', 'name' => 'Subscriber User'],
        ];

        foreach ($roleUsers as $roleName => $info) {
            // Ensure the role exists (RoleSeeder should have created it already)
            $r = Role::firstOrCreate(['name' => $roleName], ['guard_name' => 'web']);

            $u = User::firstOrCreate(
                ['email' => $info['email']],
                [
                    'name' => $info['name'],
                    'password' => Hash::make('password'),
                ]
            );

            // Ensure username reflects the role name (e.g., 'editor', 'author')
            $roleBasedUsername = \Illuminate\Support\Str::slug($roleName);
            if (! $u->username || $u->username !== $roleBasedUsername) {
                // make username unique by appending a number if needed
                $base = $roleBasedUsername;
                $candidate = $base;
                $i = 1;
                while (User::where('username', $candidate)->where('id', '!=', $u->getKey())->exists()) {
                    $candidate = $base . '-' . $i++;
                }
                $u->username = $candidate;
                $u->save();
            }

            try {
                $columns = \Illuminate\Support\Facades\Schema::getColumnListing('role_user');
                if (is_array($columns) && in_array('id', $columns, true)) {
                    $u->assignRole($r);
                } else {
                    \Illuminate\Support\Facades\DB::table('role_user')->insert([
                        'role_id' => $r->getKey(),
                        'user_id' => $u->getKey(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } catch (\Throwable $e) {
                $u->assignRole($r);
            }
        }
    }
}
