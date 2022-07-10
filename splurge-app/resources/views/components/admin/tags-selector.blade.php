<div id="tags_selector_app">
  
</div>

@push('scripts')
    <script   nonce="{{ csp_nonce() }}">
        Splurge.admin.tags.renderSelector(document.querySelector('#tags_selector_app'), {
            tags: {!! Js::from($tags) !!},
            inputName: '{{$name}}'
        });
    </script>
@endpush