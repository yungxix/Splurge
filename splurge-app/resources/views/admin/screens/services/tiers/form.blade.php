@php
    $url = $tier->id < 1 ? route('admin.service_detail.tiers.store', $service) :
     route('admin.service_detail.tiers.update', ['service' => $tier->service_id, 'tier' => $tier->id]);

@endphp
<div class="flex">
    <div class="w-full md:w-2/3">
        <form action="{{ $url }}" method="POST">
            @csrf
            @if ($tier->id > 0)
                <input type="hidden" name="_method" value="PATCH" />
            @endif
            <div class="mb-4">
                <x-label :field="'name'" :errors="$errors">
                    Name of tier
                </x-label>
                <x-input type="text" name="name" placeholder="Name of tier" class="block w-full" :value="$tier->name" :field="'name'" :errors="$errors"></x-input>
                @error('name')
                    <p class="text-red-700">{{ $message }}</p>
                @enderror
            </div>
        
            <div class="mb-4">
                <x-label :field="'price'" :errors="$errors">
                    Price
                </x-label>
                <x-input type="number"  placeholder="Price of tier" class="block w-full"  name="price" :value="$tier->price" :field="'price'" :errors="$errors"></x-input>
                @error('price')
                    <p class="text-red-700">{{ $message }}</p>
                @enderror
            </div>
        
        
            <div class="mb-4">
                <x-label :field="'description'" :errors="$errors">
                    Description
                </x-label>
                <x-textarea name="description"  placeholder="Description of tier" class="block w-full"  :field="'description'" :errors="$errors">{{$tier->description}}</x-textarea>
                @error('description')
                    <p class="text-red-700">{{ $message }}</p>
                @enderror
            </div>
        
            <div class="mb-4">
                <x-label :field="'options'" :errors="$errors">
                    Options
                </x-label>
                <div id="tier_options_container">
                    <span class="animate-pulse text-center">...</span>

                </div>
                @error('options')
                    <p class="text-red-700">{{ $message }}</p>
                @enderror
            </div>
        
        
            <div class="mb-4">
                <x-label :field="'footer_message'" :errors="$errors">
                    Message after options
                </x-label>
                <x-textarea name="footer_message" placeholder="Bottom message" class="block w-full"  :field="'footer_message'" :errors="$errors">{{$tier->footer_message}}</x-textarea>
                @error('footer_message')
                    <p class="text-red-700">{{ $message }}</p>
                @enderror
            </div>
        
            <div class="mb-4 flex flex-row gap-x-4 justify-end items-center p-4">
                <button class="btn" type="submit">
                    Save tier
                </button>
                <a href="{{ route('admin.service_detail.tiers.index', $service) }}">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>


@push('scripts')
    <script>
        Splurge.admin.serviceTiers.renderOptionsEditor(document.querySelector("#tier_options_container"), {
            name: "options",
            value: {{ Js::from($tier->options ?: []) }}
        })
    </script>
@endpush