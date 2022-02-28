<header class="mb-8">
    <img class="mx-auto" src="{{ splurge_asset($post->image_url) }}" alt="{{ $post->name }} post image" />
    <div class="container mx-auto mt-4">
        <h3 class="text-3xl text-center">{{ $post->name }}</h3>
        <div class="mt-4 flex flex-row justify-end items-center gap-x-8">
            <span>
            @unless (is_null($post->author))
                By {{ $post->author->name }}, &nbsp;
            @endunless
                {{ $post->created_at->diffForHumans() }}
            </span>
            <a class="link" href="{{ route('admin.post_detail.post_items.create', $post) }}">
                Add section
            </a>
            <a class="link" href="{{ route('admin.posts.edit', $post) }}">
                Edit
            </a>
            <a class="link" href="{{ route('admin.posts.create') }}">
                New post/event
            </a>
        </div>
    </div>
</header>