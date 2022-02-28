@php
$url = is_null($gallery_item->id) ? route('admin.gallery_detail.gallery_items.store', ['gallery' => $gallery->id]) :
 route('admin.gallery_detail.gallery_items.update', ['gallery' => $gallery->id, 'gallery_item' => $gallery_item->id]);
$picture_limit = 4;


$cancel_url = is_null($gallery_item->id) ? route('admin.gallery.show', ['gallery' => $gallery->id])
            : route('admin.gallery_detail.gallery_items.show', ['gallery' => $gallery->id, 'gallery_item' => $gallery_item->id]);
@endphp


<form enctype="multipart/form-data" method="POST" action="{{ $url }}">
    @csrf
    @unless (is_null($gallery_item->id))
        <input type="hidden" name="_method" value="PATCH" />
    @endunless

    <div class="">
        <div class="">
            <x-forms.form-group :field="'heading'" :errors="$errors">
                <x-slot:label>
                    <x-label for="heading">
                        Title/Caption
                    </x-label>
                </x-slot:label>
                <input type="text" id="heading" name="heading" class="control w-full"
                     value="{{ $gallery_item->heading }}" required/>
            </x-forms.form-group>


            <x-forms.form-group :field="'content'" :errors="$errors">
                <x-slot:label>
                    <x-label for="content">
                        Page story
                    </x-label>
                </x-slot:label>
                <textarea id="content" name="content" class="control w-full" required>
                    {{ $gallery_item->content }}
                </textarea>
            </x-forms.form-group>

            <p class="p-4 bg-stone-300 rounded-md m-4">
                <em>You can add up to {{ $picture_limit }} pictures</em>
            </p>
            <div class="grid grid-cols-2 divide-y divide-pink-700 gap-4">
                @foreach (range(1, $picture_limit) as $i)
                    <div>
                        #{{ $loop->index + 1 }}
                        <div class="mb-2">
                            <x-label for="name_{{ $loop->index }}">
                                Short description of picture
                            </x-label>
                            <x-input id="name_{{ $loop->index }}" type="text" class="w-full" name="media[][name]"></x-input>
                            @error('media.' . $loop->index . '.name')
                                <span class="block text-red-700 mt-2">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <x-label for="file_{{ $loop->index }}">
                                Picture
                            </x-label>
                            <input type="file" name="media[][medium]" id="file_{{ $loop->index }}" accept="image/*"  />
                            @error('media.' . $loop->index . '.medium')
                                <span class="block text-red-700 mt-2">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>                
                @endforeach
            </div>
        </div>
    </div>



    <div class="mt-2 flex gap-x-4 flex-row items-center p-4 justify-end">
        <a href="{{ $cancel_url }}" class="btn">
            Cancel
        </a>
        <button class="btn" type="submit">
            Save page
        </button>
    </div>

</form>
