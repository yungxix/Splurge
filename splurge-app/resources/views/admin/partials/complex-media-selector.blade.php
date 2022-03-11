<div id="image_selector_app" class="mt-8"></div>

@push('scripts')
    <script  nonce="{{ csp_nonce() }}">
        Splurge.admin.uploader.renderAltSelector(document.querySelector('#image_selector_app'), {
            fileInputName: '{{ $file_input_name }}',
            captionInputName: '{{ $caption_input_name }}',
            mediumInputName: '{{ $medium_input_name }}'
        });
    </script>
@endpush