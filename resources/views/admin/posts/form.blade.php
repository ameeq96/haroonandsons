<section class="admin-panel form-panel">
    <div class="panel-heading">
        <div><p class="admin-kicker">POST DETAILS</p><h2>{{ $editing ? 'Update your post' : 'Write a new post' }}</h2></div>
        <a class="table-link" href="{{ route('admin.posts.index') }}">Back to posts</a>
    </div>
    @if($errors->any())
        <div class="form-errors" role="alert"><strong>Some fields need attention.</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    @php
        $existingImages = array_values(array_unique(array_filter(array_merge([$post->featured_image], $post->gallery_images ?? []))));
        $imageOrder = array_values(array_unique(array_merge(array_filter((array) old('image_order', []), fn ($image) => is_string($image) && in_array($image, $existingImages, true)), $existingImages)));
    @endphp

    <form method="POST" action="{{ $editing ? route('admin.posts.update', $post) : route('admin.posts.store') }}" enctype="multipart/form-data" class="post-editor" data-post-editor>
        @csrf
        @if($editing) @method('PUT') @endif

        <div class="editor-main">
            <div class="bilingual-fields">
                <h3>Post content — English &amp; 简体中文</h3>
                <p>Enter both languages manually. The website language switch will display the matching version.</p>
                <label>Title (English) <span class="required">*</span><input name="title" value="{{ old('title', $post->title) }}" maxlength="180" required></label>
                <label>标题（简体中文）<input name="title_zh" value="{{ old('title_zh', $post->title_zh) }}" maxlength="180" placeholder="请输入中文标题"></label>
                <label>URL slug <input name="slug" value="{{ old('slug', $post->slug) }}" maxlength="200" placeholder="Leave blank to create from English title"><small>Letters, numbers, hyphens, and underscores only.</small></label>
                <label>Short excerpt (English)<textarea name="excerpt" rows="3" maxlength="500">{{ old('excerpt', $post->excerpt) }}</textarea></label>
                <label>简短摘要（简体中文）<textarea name="excerpt_zh" rows="3" maxlength="500">{{ old('excerpt_zh', $post->excerpt_zh) }}</textarea></label>
                <label>Full post content (English) <span class="required">*</span><textarea name="content" rows="12" required>{{ old('content', $post->content) }}</textarea><small>HTML is escaped for safety; plain text and line breaks are supported.</small></label>
                <label>文章内容（简体中文）<textarea name="content_zh" rows="12">{{ old('content_zh', $post->content_zh) }}</textarea></label>
            </div>
        </div>

        <aside class="editor-side">
            <label>Status
                <select name="status">
                    <option value="draft" @selected(old('status', $post->status ?: 'draft') === 'draft')>Draft</option>
                    <option value="published" @selected(old('status', $post->status) === 'published')>Published</option>
                </select>
            </label>
            <label>Publication date <input type="datetime-local" name="published_at" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}"><small>Leave empty to publish immediately.</small></label>

            <div class="bilingual-fields">
                <h3>Category</h3>
                <label>Category (English)<input name="category" value="{{ old('category', $post->category) }}" maxlength="100"></label>
                <label>分类（简体中文）<input name="category_zh" value="{{ old('category_zh', $post->category_zh) }}" maxlength="100"></label>
            </div>

            <label>Featured image
                <input type="file" name="featured_image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                <small>JPG, PNG or WebP. Maximum 5 MB per image. A new featured image replaces the current cover.</small>
            </label>
            <label>Additional images
                <input type="file" name="gallery_images[]" multiple accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                <small>Select multiple images. You can add more images when editing later.</small>
            </label>

            @if($imageOrder)
                <fieldset class="editor-gallery">
                    <legend>Saved images</legend>
                    <small>Choose the cover, reorder images, or mark them for removal. Changes apply when you save.</small>
                    <ol class="editor-gallery-list" data-gallery-list>
                        @foreach($imageOrder as $image)
                            <li class="editor-gallery-item">
                                <input type="hidden" name="image_order[]" value="{{ $image }}">
                                <img class="editor-gallery-thumbnail" src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($image) }}" alt="Saved post image {{ $loop->iteration }}">
                                <div class="editor-gallery-options">
                                    <label><input type="radio" name="cover_image" value="{{ $image }}" @checked(old('cover_image', $post->featured_image) === $image)> Cover</label>
                                    <label><input type="checkbox" name="remove_images[]" value="{{ $image }}" @checked(in_array($image, (array) old('remove_images', []), true))> Remove</label>
                                </div>
                                <div class="editor-gallery-order">
                                    <button type="button" data-move="up" @disabled($loop->first)>Move up</button>
                                    <button type="button" data-move="down" @disabled($loop->last)>Move down</button>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </fieldset>
            @endif

            <div class="post-video-upload">
                <h3>Optional video for this post</h3>
                <p>Upload the video here. It will appear inside this same post on Latest Updates and the post detail page. No separate video post is created.</p>
                @if($editing && $post->video_url)
                    <video class="admin-post-video-preview" controls preload="metadata">
                        <source src="{{ $post->video_url }}" type="{{ $post->video_mime_type ?: 'video/mp4' }}">
                    </video>
                    <label class="remove-video-option"><input type="checkbox" name="remove_video" value="1"> Remove current video when saving</label>
                @endif
                <label>Upload video (MP4, MOV, WebM, M4V or AVI)
                    <input type="file" name="video" accept="video/mp4,video/webm,video/quicktime,video/x-m4v,video/x-msvideo,.mp4,.mov,.webm,.m4v,.avi">
                    <small>No application-level size cap is applied. The real maximum depends on PHP upload settings, available disk space, and your server.</small>
                </label>
            </div>

            <div class="bilingual-fields">
                <h3>SEO settings</h3>
                <label>SEO title (English)<input name="seo_title" value="{{ old('seo_title', $post->seo_title) }}" maxlength="180"></label>
                <label>SEO 标题（简体中文）<input name="seo_title_zh" value="{{ old('seo_title_zh', $post->seo_title_zh) }}" maxlength="180"></label>
                <label>Meta description (English)<textarea name="meta_description" rows="3" maxlength="320">{{ old('meta_description', $post->meta_description) }}</textarea></label>
                <label>Meta description（简体中文）<textarea name="meta_description_zh" rows="3" maxlength="320">{{ old('meta_description_zh', $post->meta_description_zh) }}</textarea></label>
            </div>
        </aside>

        <div class="editor-actions">
            <button class="admin-button primary" type="submit">{{ $editing ? 'Save changes' : 'Create post' }}</button>
            @if($editing)<a class="admin-button secondary" href="{{ route('admin.posts.preview', $post) }}" target="_blank" rel="noopener">Preview</a>@endif
        </div>
    </form>

    <script>
    (() => {
        const form = document.querySelector('[data-post-editor]');
        const list = form?.querySelector('[data-gallery-list]');
        if (!form) return;
        const syncGallery = () => {
            if (!list) return;
            const items = [...list.children];
            items.forEach((item, index) => {
                const cover = item.querySelector('input[name="cover_image"]');
                const remove = item.querySelector('input[name="remove_images[]"]');
                if (cover) { cover.disabled = !!remove?.checked; if (cover.disabled) cover.checked = false; }
                item.querySelector('[data-move="up"]').disabled = index === 0;
                item.querySelector('[data-move="down"]').disabled = index === items.length - 1;
            });
            const covers = [...list.querySelectorAll('input[name="cover_image"]')];
            if (covers.length && !covers.some(c => c.checked && !c.disabled)) covers.find(c => !c.disabled)?.click();
        };
        list?.addEventListener('change', syncGallery);
        list?.addEventListener('click', e => {
            const button = e.target.closest('[data-move]');
            if (!button || button.disabled) return;
            const item = button.closest('li');
            const sibling = button.dataset.move === 'up' ? item.previousElementSibling : item.nextElementSibling;
            if (!sibling) return;
            button.dataset.move === 'up' ? sibling.before(item) : sibling.after(item);
            syncGallery();
        });
        syncGallery();
    })();
    </script>
</section>
