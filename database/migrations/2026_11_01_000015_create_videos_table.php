<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table): void {
            $table->id();
            $table->string('title_en', 255);
            $table->string('title_zh', 255)->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_zh')->nullable();
            $table->string('video_path', 500)->comment('Path relative to storage/app/public/');
            $table->string('thumbnail_path', 500)->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft')->index();
            $table->unsignedBigInteger('file_size')->nullable()->comment('Size in bytes');
            $table->string('mime_type', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
