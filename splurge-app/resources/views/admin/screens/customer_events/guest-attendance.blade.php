@if ($past)
    @if (is_null($model->attended_at))
        <em>
            Was not marked as attended
        </em>
    @else
        At {{ $model->attendance_at->format('h:i A') }}
    @endif
@else
    <form method="POST" action="{{ route('admin.customer_event_detail.guests.update', ['guest' => $model->id, 'customer_event' => $customer_event->id]) }}">
        @csrf
        @method('PATCH')
        <label>
            Attended at:
        </label>
        <input type="hidden" name="rdir" value="back" />
        <input type="time" class="block my-2" value="{{ $model->getAttendanceTime() ?: '' }}" name="guest[attendance_at]" />
        <button type="submit" class="btn block">
            Update
        </button>
    </form>    
@endif