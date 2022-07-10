@php
    use App\Support\HtmlHelper;

@endphp


@unless (($messages)->isEmpty())
    <div id="flash_container" class="absolute z-50 flex flex-col mt-24 justify-end gap-y-4 left-auto right-0 overflow-visible">
        
    </div>    


    @push('scripts')
        <script  nonce="{{ csp_nonce() }}">
            Splurge.flash.render(document.querySelector('#flash_container'), {
                messages: {!! Js::from($messages->all()) !!},
                delay: 5000
            })
        </script>
    @endpush
@endunless

