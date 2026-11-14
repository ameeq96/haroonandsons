<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPostsTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_uses_the_haroon_and_sons_site_content(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('HAROON & SONS')
            ->assertSee('Our Vision & Mission')
            ->assertSee('assets/images/logoo.png');
    }

    public function test_public_pages_show_published_posts_but_hide_drafts(): void
    {
        Post::create([
            'title' => 'Visible update',
            'slug' => 'visible-update',
            'content' => 'A public company update.',
            'status' => 'published',
            'published_at' => now()->subMinute(),
        ]);
        Post::create([
            'title' => 'Private draft title',
            'slug' => 'private-draft-title',
            'content' => 'This draft must remain private.',
            'status' => 'draft',
        ]);

        $this->get('/posts')->assertOk()->assertSee('Visible update')->assertDontSee('Private draft title');
        $this->get('/posts/visible-update')->assertOk()->assertSee('A public company update.');
        $this->get('/posts/private-draft-title')->assertNotFound();
        $this->get('/posts/not-a-post')->assertNotFound();
    }

    public function test_public_updates_can_be_marked_read_in_the_visitor_session(): void
    {
        $this->from('/posts')->post('/updates/read')->assertRedirect('/posts');
        $this->assertNotNull(session('public_updates_read_at'));
    }
}
