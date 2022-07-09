@php
    if (!isset($cancel_url)) {
        $cancel_url = is_null($service->id) ? route('admin.services.index') : route('admin.services.show', $service);
    }
    $url = is_null($service->id) ? route('admin.services.store') : route('admin.services.update', $service);

    $taggable = [
        'id' => $service->id ?: -1,
        'type' => 'service'
    ];
@endphp
<form  enctype="multipart/form-data" class="mt-4" action="{{ $url }}" method="POST">
    @unless (is_null($service->id))
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
            @unless (empty($service->image_url))
                <figure>
                    <img src="{{ splurge_asset($service->image_url) }}" class="max-h-32" />
                    <figcaption>
                        Current image
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
        <input text="text" id="service_name" name="name" class="control w-full" value="{{ $service->name }}" required />
    </x-forms.form-group>



    <x-forms.form-group :field="'description'" :errors="$errors">
        <x-slot:label>
            <x-label for="description">
                Description
            </x-label>
        </x-slot:label>
        <textarea name="description" rows="15" class="control w-full" required>{{ $service->description }}</textarea>
    </x-forms.form-group>


    <x-forms.form-group :field="'display'" :errors="$errors">
        <x-slot:label>
            <x-label for="display">
                Display
            </x-label>
        </x-slot:label>
        <select name="display" class="control">
            @foreach (config('view.services.displays') as $key => $label)
            <option @selected($service->display == $key) value="{{ $key }}"> {{  $label }}</option>
            @endforeach
        </select>
    </x-forms.form-group>


    <x-forms.form-group :field="'tags'" :errors="$errors">
        <x-slot:label>
            <x-label>
                Tags
            </x-label>
        </x-slot:label>
        <p class="bg-gray-200 rounded-md m-4 p-4">
            <em>Adding tags to services will create links between the service and events or a selection of gallery items</em>
        </p>
        <x-admin.tags-selector :name="'tags[]'" :taggable="$taggable"></x-admin.tags-selector>
    </x-forms.form-group>

    <div class="p-4 flex flex-row items-center gap-x-4 justify-end">
        <a href="{{ $cancel_url }}" class="btn">Cancel</a>
        <button type="submit" class="btn">
            Save
        </button>
    </div>
</form>