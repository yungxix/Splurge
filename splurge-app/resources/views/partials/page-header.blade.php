<header class="bg-white shadow mb-4">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title }}
            @if (isset($sub_title))
                <span class="text-lg text-gray-600 ml-8">{{ $sub_title }}</span>
            @endif
        </h2>
    </div>
</header>