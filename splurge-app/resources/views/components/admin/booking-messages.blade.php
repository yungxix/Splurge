@unless ($messages->isEmpty())
<div class="p-2">
    <h4 class="font-bold mb-2">Messages</h4>
    <div class="flex flex-col divide-y">
        @foreach ($messages as $message)
            <a href="{{ route("admin.messages.show", $message) }}" class="block p-2 bg-white mb-4 hover:bg-gray-100 focus:bg-gray-100">
                <span class="font-thin italic float-right">
                    {{$message->created_at->diffForHumans()}}
                </span>
                <h5 class="text-md">{{ $message->subject }}</h5>
                <br class="clear-both" />
            </a>
        @endforeach
    </div>

    

    <div class="mt-8">
        {{$messages->links()}}
    </div>    <!-- No surplus words or unnecessary actions. - Marcus Aurelius -->
</div>    

@endunless
