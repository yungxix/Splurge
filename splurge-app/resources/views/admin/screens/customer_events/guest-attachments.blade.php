@unless (empty($model->$attribute))
    <ul class="list-inside list-disc">
        @foreach ($model->$attribute as $label => $value)
            <li>
                <span class="mr-4">
                    {{ $label }}
                </span>
                
                @if ($value === TRUE|| $value === 'yes')
                 <svg class="text-green-800 w-6 h-6 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                  </svg>
                @elseif ($value === FALSE || $value === 'no')
                <svg class="text-red-800 w-6 h-6 inline"  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                @else
                  <span class="text-gray-700"> {{ $value }}</span>
                @endif
            </li>
        @endforeach
    </ul>
@else
    <em>N/A</em>
@endunless
