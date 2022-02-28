@php
    use App\Support\HtmlHelper;    
@endphp


@unless (empty($messages))
    <div id="flash_container" class="absolute z-50 flex flex-col justify-end gap-y-4 left-auto right-0 overflow-visible">
        
    </div>    


    @push('scripts')
        <script>
            Splurge.flash.render(document.querySelector('#flash_container'), {
                messages: {!! Js::from($messages->all()) !!},
                delay: 5000
            })
        </script>
    @endpush
@endunless

