<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('blog_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('blog_post_category', function (Blueprint $table) {
            $table->foreignId('blog_post_id')->constrained('blog_posts')->onDelete('cascade');
            $table->foreignId('blog_category_id')->constrained('blog_categories')->onDelete('cascade');
            $table->primary(['blog_post_id', 'blog_category_id']);
        });

        Schema::create('blog_post_tag', function (Blueprint $table) {
            $table->foreignId('blog_post_id')->constrained('blog_posts')->onDelete('cascade');
            $table->foreignId('blog_tag_id')->constrained('blog_tags')->onDelete('cascade');
            $table->primary(['blog_post_id', 'blog_tag_id']);
        });

        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_post_id')->constrained('blog_posts')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->text('content');
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });

        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->string('subtitle')->nullable()->after('title');
            $table->integer('reading_time')->nullable()->after('content');
            $table->boolean('is_featured')->default(false)->after('featured_image');
            $table->text('faq')->nullable()->after('seo_description'); // Store FAQ dynamic JSON rows
            $table->text('attachments')->nullable()->after('faq'); // Store attachments dynamic JSON rows
        });

        Schema::table('users', function (Blueprint $table) {
            $table->text('bio')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('twitter_handle')->nullable();
            $table->string('github_handle')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['bio', 'profile_image', 'twitter_handle', 'github_handle']);
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn(['subtitle', 'reading_time', 'is_featured', 'faq', 'attachments']);
        });

        Schema::dropIfExists('newsletter_subscribers');
        Schema::dropIfExists('blog_comments');
        Schema::dropIfExists('blog_post_tag');
        Schema::dropIfExists('blog_post_category');
        Schema::dropIfExists('blog_tags');
        Schema::dropIfExists('blog_categories');
    }
};
