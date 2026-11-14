<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table): void {
            $table->string('title_zh', 180)->nullable()->after('title');
            $table->string('excerpt_zh', 500)->nullable()->after('excerpt');
            $table->longText('content_zh')->nullable()->after('content');
            $table->string('category_zh', 100)->nullable()->after('category');
            $table->string('seo_title_zh', 180)->nullable()->after('seo_title');
            $table->string('meta_description_zh', 320)->nullable()->after('meta_description');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table): void {
            $table->dropColumn([
                'title_zh',
                'excerpt_zh',
                'content_zh',
                'category_zh',
                'seo_title_zh',
                'meta_description_zh',
            ]);
        });
    }
};
