<div id="assignments_app">

</div>
@push('scripts')
    <script  nonce="{{ csp_nonce() }}">
        Splurge.admin.tags.renderAssignment(document.getElementById('assignments_app'), {
            taggable: {!! Js::from($taggable) !!},
            indexUrl: '{{ route('admin.tags.index') }}',
            baseURL: '{{ route('admin.tags.index') }}'
        })
    </script>
@endpush