@php
    use App\Support\CommunicationHelper;
    $message_columns = [
        'To',
        'From',    
        'Subject',
        'On',
        'Sent'
    ];
    
    $helper = new CommunicationHelper($messages);

    $breadcrumbItems = [
    [
            'text' => 'Dashboard',
            'url' => route('admin.admin_dashboard')
    ],[
        'text' => 'Messages',
        'url' => '#'
    ]
    ];

@endphp

@extends('layouts.admin')

@section('title', 'Messages')


@section('content')
    <x-breadcrumbs :items="$breadcrumbItems"></x-breadcrumbs>
    @include('partials.page-header', ['title' => 'Messages'])
    <div class="container mx-auto mt-4">
        <div class="md:flex flex-row">
            <div class="md:w-2/3 pr-4">
                <x-simple-table :columns="$message_columns">
                    <x-slot:footer>
                        <div class="float-right">
                            {{$messages->links()}}
                        </div>
                        <br class="clear-both" />
                    </x-slot:footer>
                    @foreach ($messages as $message)
                        <tr>
                            <td class="px-6 py-3 text-left">
                                <a class="link" href="{{ route('admin.messages.show', $message) }}">
                                    {{$helper->resolveReceiver($message)}}
                                </a>
                            </td>    
                            <td class="px-6 py-3 text-left">
                                {{$helper->resolveSender($message)}}
                            </td>    
                            <td class="px-6 py-3 text-left">
                                {{$message->subject}}
                            </td>    
                            <td class="px-6 py-3 text-left">
                                {{ class_basename($message->channel_type) }}
                            </td>
                            <td class="px-6 py-3 text-left">
                                {{ $message->created_at->diffForHumans() }}
                            </td>
                        </tr>
                    @endforeach
        
                    
                </x-simple-table>
                <div class="mb-12"></div>
            </div>
            <div class="md:w-1/3">
                <x-admin.widgets.recent-bookings></x-admin.widgets.recent-bookings>
            </div>
        </div>
        
    </div>
@endsection