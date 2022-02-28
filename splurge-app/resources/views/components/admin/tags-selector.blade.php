<div id="tags_selector_app">
  
</div>

@push('scripts')
    <script>
        Splurge.admin.tags.renderSelector(document.querySelector('#tags_selector_app'), {
            tags: {!! Js::from($tags) !!},
            inputName: '{{$name}}'
        });
    </script>
@endpush