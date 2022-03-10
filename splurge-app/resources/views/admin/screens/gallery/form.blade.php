@php
    if (!isset($cancel_url)) {
        $cancel_url = route('admin.gallery.index');
    }
    if (!isset($method)) {
        $method = 'POST';
    }
    $taggable = ['id' => $gallery->id ?: -1, 'type' => 'gallery'];
@endphp
<form  enctype="multipart/form-data" class="mt-4" action="{{ $url }}" method="POST">
    @unless ($method === 'POST')
        <input type="hidden" name="_method" value="{{ $method }}" />
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
            @unless (empty($gallery->image_url))
                <figure>
                    <img src="{{ splurge_asset($gallery->image_url) }}" class="max-h-32" />
                    <figcaption>
                        Current banner
                    </figcaption>
                </figure>
            @endunless
        </div>
    </x-forms.form-group>

    <x-forms.form-group :field="'caption'" :errors="$errors">
        <x-slot:label>
            <x-label for="caption">
                Caption
            </x-label>
        </x-slot:label>
        <input text="text" id="caption" name="caption" class="control w-full" value="{{ $gallery->caption }}" required />
    </x-forms.form-group>



    <x-forms.form-group :field="'description'" :errors="$errors">
        <x-slot:label>
            <x-label for="description">
                Description
            </x-label>
        </x-slot:label>
        <textarea name="description" rows="15" class="control w-full" required>{{ $gallery->description }}</textarea>
    </x-forms.form-group>
    
    <x-forms.form-group :field="'tags'" :errors="$errors">
        <x-slot:label>
            <x-label>
                Tags
            </x-label>
        </x-slot:label>
        <div class="form-info">
            <p>
                Tags help to link gallery to services
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