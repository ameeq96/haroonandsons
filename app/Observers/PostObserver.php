<?php

namespace App\Observers;

use App\Models\AdminNotification;
use App\Models\Post;

class PostObserver
{
    public function created(Post $post): void
    {
        $this->record(
            $post,
            $post->status === 'published' ? 'published' : 'created',
            $post->status === 'published' ? 'Post published' : 'Draft created',
            $post->title
        );
    }

    public function updated(Post $post): void
    {
        if ($post->wasChanged('status')) {
            $event = $post->status === 'published' ? 'published' : 'unpublished';
            $title = $post->status === 'published' ? 'Post published' : 'Post moved to draft';
        } else {
            $event = 'updated';
            $title = 'Post updated';
        }

        $this->record($post, $event, $title, $post->title);
    }

    public function deleted(Post $post): void
    {
        AdminNotification::create([
            'event' => 'deleted',
            'title' => 'Post deleted',
            'message' => $post->title,
            'post_id' => null,
        ]);
    }

    private function record(Post $post, string $event, string $title, string $postTitle, ?int $postId = null): void
    {
        AdminNotification::create([
            'event' => $event,
            'title' => $title,
            'message' => $postTitle,
            'post_id' => $postId ?? $post->id,
        ]);
    }
}
