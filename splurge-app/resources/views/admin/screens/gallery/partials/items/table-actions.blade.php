
<div class="flex flex-row items-center justify-end p-2 gap-x-2">
    <a class="text-pink-800 hover:text-pink-900" href="{{ route('admin.gallery_detail.gallery_items.show', ['gallery' => $model->gallery_id, 'gallery_item' => $model->id]) }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
    </a>


    <a class="text-pink-800 hover:text-pink-900" href="{{ route('admin.gallery_detail.gallery_items.edit', ['gallery' => $model->gallery_id, 'gallery_item' => $model->id]) }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
        </svg>
    </a>

    <x-delete-button :url="route('admin.gallery_detail.gallery_items.destroy', ['gallery' => $model->gallery_id, 'gallery_item' => $model->id])" :prompt="'Are you sure you want to delete this item?'"></x-delete-button>
</div>