@php
    use App\Support\HtmlHelper;
    use Illuminate\Support\Str;

    
    $breadcrumbItems = [
        ['text' => 'Dashboard', 'url' => route('admin.admin_dashboard')] ,
        ['text' => 'Customer Events', 'url' => route('admin.customer_events.index')],
        ['text' => Str::limit($customer_event->name, 30), 'current' => true, 'active' => true, 'url' => '#']
    ];
@endphp
@extends('layouts.admin')

@section('title', 'Customer Event Detail')


@section('content')
    <x-breadcrumbs :items="$breadcrumbItems"></x-breadcrumbs>
    @include('partials.page-header', ['title' => Str::limit($customer_event->name, 50)])
    <section class="container mx-auto">
        <div class="flex flex-row justify-end items-center gap-8 divide-x py-4 px-2">
            <a class="link" title="Add new customer event" href="{{ route('admin.customer_events.create') }}">
                Add an event
            </a>
            <a class="link" title="Edit customer event" href="{{ route('admin.customer_events.edit', $customer_event) }}">
                Edit
            </a>
            <a class="link" title="Print Guests" target="_blank" href="{{ route('admin.customer_event_detail.guests.print', $customer_event) }}">
                Print Guests
            </a>
            <form title="Delete customer event" action="{{ route('admin.customer_events.destroy', $customer_event) }}" method="POST" onsubmit="javascript: return confirm('Are you sure you want to delete this event?')" class="inline">
                @csrf
                @method('DELETE')
                <button class="link" type="submit">
                    Delete
                </button>
            </form>
        </div>

        @include('admin.screens.customer_events.snapshot')


        <hr class="mx-auto w-4/5 my-4" />

        <div class="flex flex-row items-center justify-end gap-4">
            <a class="link" title="Add a customer to this event" href="{{ route('admin.customer_event_detail.guests.create', $customer_event) }}">
                Register a guest
            </a>
        </div>


        {{ $guest_table->render() }}

        <div class="mt-12"></div>
    </section>
@endsection

@push('scripts')
    @unless ($guests->isEmpty())
    <script src="{{ asset('js/customer_event_guests_support.js') }}"></script>    
    @endunless
@endpush