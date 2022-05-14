@php
    use Illuminate\Support\Str;

    $hostId = 'gallery_images_' . Str::random(6);
@endphp
@unless ($items->isEmpty())
<div class="p-2 lg:p-4 recent-gallery-media-items">
    <h4 class="text-gray-800 text-lg">Recent Pictures</h4>
    <div id="{{ $hostId }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto animate-spin h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
          </svg>
    </div>
    <div class="flex flex-row items-center justify-end">
        <a class="link" href="{{ route('gallery.index') }}">
            See more
        </a>
    </div>

    @push('scripts')
        <script  nonce="{{ csp_nonce() }}">
            window.addEventListener('load', function () {
                Splurge.slides.render(document.getElementById('{{ $hostId }}'), {
                    items: {{{ Js::from($items->all()) }}}
                });
            });
        </script>
    @endpush
</div>
@endunless