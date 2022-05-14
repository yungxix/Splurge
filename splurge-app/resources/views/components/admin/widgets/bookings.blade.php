@php
    use Illuminate\Support\Str;
@endphp
@props(['title', 'bookings', 'url' => null, 'url_text' => 'See all'])
@unless ($bookings->isEmpty())
<div class="rounded flex flex-col divide-y shadow mb-8 bg-gray-100 p-2">
    <h4 class="text-lg font-bold mb-4">{{ $title }}</h4>
    @foreach ($bookings as $booking)
        <a href="{{ route('admin.bookings.show', $booking) }}" class="block p-2 bg-white mb-4 hover:bg-gray-100 focus:bg-gray-100">
            <div class="text-right text-gray-700">
                {{$booking->event_date->diffForHumans()}}
            </div>
            <div class="font-bold">{{ Str::limit($booking->description, 90, '...') }}</div>
            <p class="text-sm text-right">
                Posted {{ $booking->created_at->diffForHumans() }}
            </p>
        </a>
    @endforeach
    @unless (empty($url))
        <p class="text-right">
            <a class="link" href="{{ $url }}">
                {{$url_text}}
            </a>
        </p>
    @endunless
</div>    
@endunless
