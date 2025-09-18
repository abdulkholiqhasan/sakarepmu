<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            // SQLite can't drop primary keys — recreate the table without the `id` column.
            DB::statement('CREATE TABLE role_user_new (
                role_id TEXT NOT NULL,
                user_id TEXT NOT NULL,
                created_at TEXT,
                updated_at TEXT,
                PRIMARY KEY (role_id, user_id),
                FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )');

            // Copy existing data (ignore id)
            DB::statement('INSERT INTO role_user_new (role_id, user_id, created_at, updated_at) SELECT role_id, user_id, created_at, updated_at FROM role_user');

            DB::statement('DROP TABLE role_user');
            DB::statement('ALTER TABLE role_user_new RENAME TO role_user');
        } else {
            // MySQL requires foreign keys to be dropped before dropping indexes/columns.
            // Drop foreign keys if they exist, then drop the unique index and primary key,
            // drop the `id` column and create a composite primary key on (role_id, user_id),
            // then recreate the foreign keys.

            // Attempt to drop foreign keys (ignore errors if they don't exist)
            try {
                DB::statement('ALTER TABLE `role_user` DROP FOREIGN KEY `role_user_role_id_foreign`');
            } catch (\Throwable $e) {
                // ignore
            }

            try {
                DB::statement('ALTER TABLE `role_user` DROP FOREIGN KEY `role_user_user_id_foreign`');
            } catch (\Throwable $e) {
                // ignore
            }

            // Drop unique index if present
            try {
                DB::statement('ALTER TABLE `role_user` DROP INDEX `role_user_role_id_user_id_unique`');
            } catch (\Throwable $e) {
                // ignore
            }

            // Drop primary key (backed by `id` column)
            DB::statement('ALTER TABLE `role_user` DROP PRIMARY KEY');

            // Drop the `id` column
            Schema::table('role_user', function (Blueprint $table) {
                $table->dropColumn('id');
            });

            // Make a composite primary key on (role_id, user_id)
            DB::statement('ALTER TABLE `role_user` ADD PRIMARY KEY (`role_id`, `user_id`)');

            // Recreate foreign keys
            DB::statement('ALTER TABLE `role_user` ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE');
            DB::statement('ALTER TABLE `role_user` ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE');
        }
    }

    public function down()
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            // Recreate the old table with `id` as a TEXT primary key. We cannot recover original ids,
            // but this down migration is mainly for symmetry in development/testing environments.
            DB::statement('CREATE TABLE role_user_old (
                id TEXT PRIMARY KEY,
                role_id TEXT NOT NULL,
                user_id TEXT NOT NULL,
                created_at TEXT,
                updated_at TEXT
            )');

            // Copy data into new table (generate random ids)
            DB::statement('INSERT INTO role_user_old (id, role_id, user_id, created_at, updated_at) SELECT hex(randomblob(16)), role_id, user_id, created_at, updated_at FROM role_user');

            DB::statement('DROP TABLE role_user');
            DB::statement('ALTER TABLE role_user_old RENAME TO role_user');

            // Recreate unique index and foreign keys
            try {
                DB::statement('CREATE UNIQUE INDEX role_user_role_id_user_id_unique ON role_user (role_id, user_id)');
            } catch (\Throwable $e) {
            }
        } else {
            // Drop foreign keys we added
            try {
                DB::statement('ALTER TABLE `role_user` DROP FOREIGN KEY `role_user_role_id_foreign`');
            } catch (\Throwable $e) {
                // ignore
            }

            try {
                DB::statement('ALTER TABLE `role_user` DROP FOREIGN KEY `role_user_user_id_foreign`');
            } catch (\Throwable $e) {
                // ignore
            }

            // Drop composite primary key
            DB::statement('ALTER TABLE `role_user` DROP PRIMARY KEY');

            // Add back `id` UUID primary column
            Schema::table('role_user', function (Blueprint $table) {
                $table->uuid('id')->primary();
            });

            // Recreate foreign keys
            DB::statement('ALTER TABLE `role_user` ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE CASCADE');
            DB::statement('ALTER TABLE `role_user` ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE');

            // Recreate unique index on (role_id, user_id)
            try {
                DB::statement('ALTER TABLE `role_user` ADD UNIQUE `role_user_role_id_user_id_unique` (`role_id`, `user_id`)');
            } catch (\Throwable $e) {
                // ignore
            }
        }
    }
};
