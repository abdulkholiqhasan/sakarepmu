<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add WP-like fields to posts table if it exists
        if (Schema::hasTable('posts')) {
            Schema::table('posts', function (Blueprint $table) {
                if (!Schema::hasColumn('posts', 'author_id')) {
                    $table->uuid('author_id')->nullable()->after('id');
                }
                if (!Schema::hasColumn('posts', 'excerpt')) {
                    $table->text('excerpt')->nullable()->after('content');
                }
                if (!Schema::hasColumn('posts', 'featured_image')) {
                    $table->string('featured_image')->nullable()->after('excerpt');
                }
                if (!Schema::hasColumn('posts', 'status')) {
                    $table->string('status')->default('draft')->after('featured_image');
                }
                if (!Schema::hasColumn('posts', 'post_type')) {
                    $table->string('post_type')->default('post')->after('status');
                }
            });
        }

        // Create pivot table post_tag
        if (!Schema::hasTable('post_tag')) {
            Schema::create('post_tag', function (Blueprint $table) {
                $table->uuid('post_id');
                $table->uuid('tag_id');
                $table->primary(['post_id', 'tag_id']);
                // $table->foreign('post_id')->references('id')->on('posts');
                // $table->foreign('tag_id')->references('id')->on('tags');
            });
        }

        // Create pivot table category_post
        if (!Schema::hasTable('category_post')) {
            Schema::create('category_post', function (Blueprint $table) {
                $table->uuid('post_id');
                $table->uuid('category_id');
                $table->primary(['post_id', 'category_id']);
                // $table->foreign('post_id')->references('id')->on('posts');
                // $table->foreign('category_id')->references('id')->on('categories');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('post_tag')) {
            Schema::dropIfExists('post_tag');
        }
        if (Schema::hasTable('category_post')) {
            Schema::dropIfExists('category_post');
        }

        if (Schema::hasTable('posts')) {
            Schema::table('posts', function (Blueprint $table) {
                if (Schema::hasColumn('posts', 'author_id')) {
                    $table->dropColumn('author_id');
                }
                if (Schema::hasColumn('posts', 'excerpt')) {
                    $table->dropColumn('excerpt');
                }
                if (Schema::hasColumn('posts', 'featured_image')) {
                    $table->dropColumn('featured_image');
                }
                if (Schema::hasColumn('posts', 'status')) {
                    $table->dropColumn('status');
                }
                if (Schema::hasColumn('posts', 'post_type')) {
                    $table->dropColumn('post_type');
                }
            });
        }
    }
};
