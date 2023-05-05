<div class="flex flex-row gap-4 items-center justify-end">
    <a class="link" href="{{ route('admin.customer_events.edit', $model) }}">
        Edit
    </a>
    <form class="inline" method="POST" onsubmit="return confirm('Are you sure you want to delete this event?')" action="{{ route('admin.customer_events.destroy', $model) }}">
        @method('DELETE')
        @csrf
        <button class="link" type="submit">
            Delete
        </button>
    </form>
</div>