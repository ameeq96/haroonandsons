<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_likes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->string('visitor_token', 64)->comment('SHA-256 hashed visitor UUID cookie');
            $table->string('ip_hash', 64)->nullable()->comment('SHA-256 hashed IP address');
            $table->timestamps();

            $table->unique(['post_id', 'visitor_token']);
            $table->index('post_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_likes');
    }
};
