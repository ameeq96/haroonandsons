<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminPostFiltersAndUploadsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_search_posts_by_title_and_filter_by_status_and_category(): void
    {
        $admin = Admin::create([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => 'a-secure-test-password',
        ]);

        Post::create(['title' => 'Winter collection', 'slug' => 'winter-collection', 'content' => 'Details', 'status' => 'draft', 'category' => 'Products']);
        Post::create(['title' => 'Summer notice', 'slug' => 'summer-notice', 'content' => 'Details', 'status' => 'published', 'category' => 'News', 'published_at' => now()]);

        $this->actingAs($admin, 'admin')
            ->get('/admin/posts?q=Winter&status=draft&category=Products')
            ->assertOk()
            ->assertSee('Winter collection')
            ->assertDontSee('Summer notice');
    }

    public function test_uploaded_featured_image_is_stored_and_removed_when_the_post_is_deleted(): void
    {
        Storage::fake('public');
        $admin = Admin::create([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => 'a-secure-test-password',
        ]);

        $this->actingAs($admin, 'admin')
            ->post('/admin/posts', [
                'title' => 'Image post',
                'content' => 'Post content.',
                'status' => 'draft',
                'featured_image' => UploadedFile::fake()->create('cover.webp', 120, 'image/webp'),
            ])
            ->assertRedirect();

        $post = Post::where('title', 'Image post')->firstOrFail();
        $this->assertNotNull($post->featured_image);
        Storage::disk('public')->assertExists($post->featured_image);

        $this->delete('/admin/posts/'.$post->id)->assertRedirect('/admin/posts');
        Storage::disk('public')->assertMissing($post->featured_image);
    }
}
