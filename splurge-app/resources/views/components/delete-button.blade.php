<a id="{{ $uniqueId }}_trigger" class="text-red-800 hover:text-red-900 {{ $buttonClass }}" href="#">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
    </svg>
</a>
<form id="{{ $uniqueId }}_form" method="POST" class="hidden w-0 h-0" action="{{ $url }}">
    @csrf
    <input type="hidden" name="_method" value="DELETE" />
</form>
@push('scripts')
    <script   nonce="{{ csp_nonce() }}">
        document.querySelector('#{{ $uniqueId }}_trigger').onclick = function (e) {
            e.preventDefault();
            @unless (empty($prompt))
                if (!confirm("{{ $prompt }}")) {
                    return false;
                }    
            @endunless
            
            document.querySelector('#{{ $uniqueId }}_form').submit();
            return false;
        };
    </script>
@endpush