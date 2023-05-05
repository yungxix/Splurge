@if (empty($guest[$attribute]))
    <p><em>N/A</em></p>
@else
    <ul>
        @foreach ($guests[$attribute] as $name => $value)
            <li>
                {{ $name }}
            </li>
        @endforeach
    </ul>
    
@endif
