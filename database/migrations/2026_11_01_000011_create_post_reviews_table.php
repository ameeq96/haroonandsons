<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_reviews', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->string('reviewer_name', 100);
            $table->string('reviewer_email', 255)->nullable();
            $table->tinyInteger('rating')->unsigned()->comment('1-5 stars');
            $table->text('review_text');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->index();
            $table->timestamps();

            $table->index(['post_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_reviews');
    }
};
