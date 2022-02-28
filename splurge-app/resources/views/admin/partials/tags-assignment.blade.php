<div id="assignments_app">

</div>
@push('scripts')
    <script>
        Splurge.admin.tags.renderAssignment(document.getElementById('assignments_app'), {
            taggable: {!! Js::from($taggable) !!},
            indexUrl: '{{ route('admin.tags.index') }}'
        })
    </script>
@endpush