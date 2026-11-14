<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table): void {
            if (! Schema::hasColumn('posts', 'video_path')) {
                $table->string('video_path', 500)->nullable()->after('gallery_images');
            }
            if (! Schema::hasColumn('posts', 'video_mime_type')) {
                $table->string('video_mime_type', 100)->nullable()->after('video_path');
            }
            if (! Schema::hasColumn('posts', 'video_size')) {
                $table->unsignedBigInteger('video_size')->nullable()->after('video_mime_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table): void {
            $columns = array_filter(['video_path', 'video_mime_type', 'video_size'], fn ($column) => Schema::hasColumn('posts', $column));
            if ($columns) $table->dropColumn($columns);
        });
    }
};
