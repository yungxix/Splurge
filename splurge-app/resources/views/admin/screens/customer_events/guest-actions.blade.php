@unless ($past)
    <form onsubmit="javacript: return confirm('Are you sure you want to delete this guest?')" action="{{ route('admin.customer_event_detail.guests.destroy', ['guest' => $model->id, 'customer_event' => $event->id]) }}">
        @csrf
        @method('DELETE')
        <div class="flex flex-row items-center justify-end gap-4">
            <a class="link" href="{{ route('admin.customer_event_detail.guests.edit', ['guest' => $model->id, 'customer_event' => $event->id]) }}">
                Edit
            </a>
            <button class="link">
                Delete
            </button>
        </div>
        
        
    </form>
@endunless