@php
    use App\Support\CommunicationHelper;
    use App\Models\Booking;
    
    $helper = new CommunicationHelper(collect([$message]));

    $breadcrumbItems = [
    [
            'text' => 'Dashboard',
            'url' => route('admin.admin_dashboard')
    ],[
        'text' => 'Messages',
        'url' => route('admin.messages.index')
    ],[
        'text' => 'Message: ' . $message->subject,
        'url' => '#'
    ]
    ];


@endphp

@extends('layouts.admin')
@section('title', "Message: " . $message->subject)
@section('content')
    <x-breadcrumbs :items="$breadcrumbItems"></x-breadcrumbs>
    @include('partials.page-header', ['title' => $message->subject])
    <div class="container mx-auto mt-4">
        <div class="md:flex flex-row">
            <div class="md:w-2/3">
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                      <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="overflow-hidden">
                            <table class="border-collapse w-full">
                                <tbody class="bg-white divide-y divide-splarge-200">
                                    <tr class="align-top">
                                        <th scope="row" class="px-3 text-left py-6">
                                            From
                                        </th>
                                        <td class="px-3 py-6">
                                            {{$helper->resolveSender($message)}}    
                                        </td>
                                    </tr>
                                    <tr class="align-top">
                                        <th scope="row" class="px-3 text-left py-6">
                                            To
                                        </th>
                                        <td class="px-3 py-6">
                                            {{$helper->resolveReceiver($message)}}    
                                        </td>
                                    </tr>
                                    <tr class="align-top">
                                        <th scope="row" class="px-3 text-left py-6">
                                            Subject
                                        </th>
                                        <td class="px-3 py-6">
                                            {{ $message->subject }}
                                        </td>
                                    </tr>
                                    @if ($message->internal)
                                    <tr class="align-top">
                                        <td colspan="2" class="px-3 py-6">
                                            <em class="px-4 py-1 bg-green-800 text-white rounded">
                                                internal message
                                            </em>
                                        </td>
                                    </tr>
                                    @endif
                                    <tr class="align-top">
                                        <td colspan="2" class="px-3 py-6">
                                            <p class="text-right mb-4">
                                               Sent {{$message->created_at->diffForHumans()}}
                                            </p>
                                            <iframe class="w-full" height="600" src="{{ route('admin.message_content', ['message' => $message->id]) }}">
        
                                            </iframe>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
            <div class="md:w-1/3">
                <div class="px-2">
                    @if ($message->channel_type === Booking::class)
                        <x-admin.widgets.booking-stats :booking="$message->channel"></x-admin.widgets.booking-stats>
                    @endif
                    <x-admin.widgets.recent-bookings></x-admin.widgets.recent-bookings>
                </div>
            </div>
        </div>
        
        
    </div>
@endsection