<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminPostsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_routes_require_sign_in(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/posts')->assertRedirect('/admin/login');
    }

    public function test_admin_can_sign_in_and_create_publish_update_and_delete_posts(): void
    {
        $admin = Admin::create([
            'name' => 'Content Admin',
            'email' => 'admin@example.test',
            'password' => Hash::make('correct horse battery staple'),
        ]);

        $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'correct horse battery staple',
        ])->assertRedirect('/admin');

        $this->post('/admin/posts', [
            'title' => 'Company announcement',
            'slug' => '',
            'excerpt' => 'A short update.',
            'content' => 'Details of our announcement.',
            'category' => 'Company news',
            'status' => 'published',
            'published_at' => '',
        ])->assertRedirect();

        $post = Post::where('title', 'Company announcement')->firstOrFail();
        $this->assertSame('company-announcement', $post->slug);
        $this->assertSame('published', $post->status);
        $this->assertNotNull($post->published_at);
        $this->assertDatabaseHas('admin_notifications', ['event' => 'published', 'post_id' => $post->id]);
        $this->get('/posts/company-announcement')->assertOk();

        $this->put('/admin/posts/'.$post->id, [
            'title' => 'Company announcement updated',
            'slug' => 'company-announcement-updated',
            'excerpt' => '',
            'content' => 'Updated announcement details.',
            'category' => 'Company news',
            'status' => 'draft',
            'published_at' => '',
        ])->assertRedirect();

        $this->get('/posts/company-announcement-updated')->assertNotFound();

        $this->delete('/admin/posts/'.$post->id)->assertRedirect('/admin/posts');
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_invalid_admin_credentials_are_rejected(): void
    {
        $this->post('/admin/login', [
            'email' => 'unknown@example.test',
            'password' => 'not-the-password',
        ])->assertSessionHasErrors('email');
    }
}
