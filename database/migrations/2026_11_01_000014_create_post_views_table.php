<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_views', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->string('visitor_token', 64)->comment('SHA-256 hashed visitor UUID cookie');
            $table->string('ip_hash', 64)->nullable();
            $table->timestamp('viewed_at')->useCurrent();
            $table->timestamps();

            $table->index(['post_id', 'visitor_token']);
            $table->index(['post_id', 'viewed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_views');
    }
};
