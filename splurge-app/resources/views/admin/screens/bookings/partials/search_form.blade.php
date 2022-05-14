@php
    $request = app("request");

    $search = $request->input("q", "");

    $state = $request->input("state", "");

    $states = config("region.states");

    $order  = $request->input("order");

    $order_attributes = [
        '(default sorting)' => '',    
        'Earliest event date' => 'event_date desc',
        'Earliest booked' => 'created_at desc',
    ];
@endphp
<div class="mt-4 md:mt-8"></div>
<form class="hidden md:block" method="GET" action="{{ route("admin.bookings.index") }}">
    <div class="flex flex-row items-center justify-end">
        <input class="control w-48" placeholder="Search" name="q" value="{{ $search }}" />
        <select class="ml-8 control" name="state">
            <option value="">(any state)</option>
            @foreach ($states as $item)
                <option value="{{ $item }}" @selected($item === $state)>
                    {{$item}}
                </option>
            @endforeach
        </select>
        <select name="order" class="control ml-4">
            @foreach ($order_attributes as $key => $value)
                <option value="{{ $value }}" @selected($value === $order)>{{ $key }}</option>
            @endforeach
        </select>
        <button class="ml-8 btn" type="submit">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>
        <a class="link ml-8" href="{{ route("admin.bookings.index") }}">
            Show all
        </a>
    </div>
    
</form>

<form class="md:hidden" method="GET" action="{{ route("admin.bookings.index") }}">
    <input class="control w-full mb-4" placeholder="Search" name="q" value="{{ $search }}" />
    <select class="w-full control block" name="state">
        <option value="">(any state)</option>
        @foreach ($states as $item)
            <option value="{{ $item }}" @selected($item === $state)>
                {{$item}}
            </option>
        @endforeach
    </select>
    <select name="order" class="block w-full control my-4">
        @foreach ($order_attributes as $key => $value)
            <option value="{{ $value }}" @selected($value === $order)>{{ $key }}</option>
        @endforeach
    </select>
    <div class="flex flex-row justify-end items-center">
        <button class="btn" type="submit">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>
        <a class="link ml-8" href="{{ route("admin.bookings.index") }}">
            Show all
        </a>
    </div>
</form>