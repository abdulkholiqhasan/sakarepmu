<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Manage\Role;
use App\Models\Manage\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the role & permission seeds.
     */
    public function run(): void
    {
        // WordPress-like roles
        $roles = [
            'administrator',
            'editor',
            'author',
            'contributor',
            'subscriber',
        ];

        foreach ($roles as $r) {
            Role::firstOrCreate(['name' => $r], ['guard_name' => 'web']);
        }
    }
}
