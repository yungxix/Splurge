@php
    use Illuminate\Support\Str;

    $hostId = 'gallery_images_' . Str::random(6);
@endphp
@unless ($items->isEmpty())
<div class="p-2 lg:p-4 recent-gallery-media-items">
    <div id="{{ $hostId }}"></div>

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