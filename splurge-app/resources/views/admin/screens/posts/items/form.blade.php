@php
    if (!isset($cancel_url)) {
        $cancel_url = route('admin.posts.show', $post);
    }
    $url = is_null($post_item->id) ? route('admin.post_detail.post_items.store', $post) :
         route('admin.post_detail.post_items.update', ['post' => $post->id, 'post_item' => $post_item->id]);

@endphp
<form  enctype="multipart/form-data" class="mt-4" action="{{ $url }}" method="POST">
    @unless (is_null($post_item->id))
        <input type="hidden" name="_method" value="PATCH" />
    @endunless
    @csrf
    
    <x-forms.form-group :field="'name'" :errors="$errors">
        <x-slot:label>
            <x-label for="service_name">
                Name
            </x-label>
        </x-slot:label>
        <input text="text" id="service_name" name="name" class="control w-full" value="{{ $post_item->name }}" required />
    </x-forms.form-group>



    <x-forms.form-group :field="'content'" :errors="$errors">
        <x-slot:label>
            <x-label for="content">
                Content
            </x-label>
        </x-slot:label>
        <textarea name="content" rows="15" class="control w-full" required>{{ $post_item->content }}</textarea>
    </x-forms.form-group>


    


    <x-forms.form-group :field="'medium_file'" :errors="$errors">
        <x-slot:label>
            <x-label>
                Picture
            </x-label>
        </x-slot:label>
        @unless (is_null($post_item->id))
        <label>
            <input type="checkbox" name="remove_picture" value="1" />
            &nbsp;&nbsp; remove existing pictures even if a new one is not provided
        </label>
        @endunless
        @include('admin.partials.complex-media-selector', ['caption_input_name' => 'medium_name', 'file_input_name' => 'medium_file', 'medium_input_name' => 'medium_id'])
    </x-forms.form-group>


    

    <div class="p-4 flex flex-row items-center gap-x-4 justify-end">
        <a href="{{ $cancel_url }}" class="btn">Cancel</a>
        <button type="submit" class="btn">
            Save
        </button>
    </div>
</form>