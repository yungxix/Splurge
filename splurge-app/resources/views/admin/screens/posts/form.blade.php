@php
    if (!isset($cancel_url)) {
        $cancel_url = is_null($post->id) ? route('admin.posts.index') : route('admin.posts.show', $post);
    }
    $url = is_null($post->id) ? route('admin.posts.store') : route('admin.posts.update', $post);

    $taggable = [
        'id' => $post->id ?: -1,
        'type' => 'post'
    ];
@endphp
<form  enctype="multipart/form-data" class="mt-4" action="{{ $url }}" method="POST">
    @unless (is_null($post->id))
        <input type="hidden" name="_method" value="PATCH" />
    @endunless
    @csrf
    <x-forms.form-group :field="'image_url'" :errors="$errors">
        <x-slot:label>
            <x-label for="image_url">
                Banner picture
            </x-label>
        </x-slot:label>
        <div class="flex flex-row gap-2">
            <input type="file" accept="images/*" name="image_url" />
            @unless (empty($post->image_url))
                <figure>
                    <img src="{{ splurge_asset($post->image_url) }}" class="max-h-32" />
                    <figcaption>
                        Current banner
                    </figcaption>
                </figure>
            @endunless
        </div>
    </x-forms.form-group>

    <x-forms.form-group :field="'name'" :errors="$errors">
        <x-slot:label>
            <x-label for="service_name">
                Name
            </x-label>
        </x-slot:label>
        <input text="text" id="service_name" name="name" class="control w-full" value="{{ $post->name }}" required />
    </x-forms.form-group>



    <x-forms.form-group :field="'description'" :errors="$errors">
        <x-slot:label>
            <x-label for="description">
                Description
            </x-label>
        </x-slot:label>
        <textarea name="description" rows="15" class="control w-full" required>{{ $post->description }}</textarea>
    </x-forms.form-group>


    <x-forms.form-group :field="'tags'" :errors="$errors">
        <x-slot:label>
            <x-label>
                Tags
            </x-label>
        </x-slot:label>
        <div class="form-info">
            <p>
                Tags help to link posts/events to services
            </p>
        </div>
        <x-admin.tags-selector :name="'tags[]'" :taggable="$taggable"></x-admin.tags-selector>
    </x-forms.form-group>

    <div class="p-4 flex flex-row items-center gap-x-4 justify-end">
        <a href="{{ $cancel_url }}" class="btn">Cancel</a>
        <button type="submit" class="btn">
            Save
        </button>
    </div>
</form>