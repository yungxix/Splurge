<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
<meta name="app-name" content="{{ config('app.name') }}" />
@isset($description)
<meta name="description" content="{{ $descrition }}" />
@else
@if (Request::is("/events*") || Request::is("/post*"))
    <meta name="description" content="{{ config('app.name') }} Events" />
@elseif (Request::is("/services*"))
<meta name="description" content="{{ config('app.name') }} Event Packages" />
@elseif (Request::is("/book*"))
<meta name="description" content="Book a service on {{ config('app.name') }}" />
@else
<meta name="description" content="{{ config('app.name') }} Events Planning" />    
@endif
@endisset

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="icon" type="image/x-icon" href="{{ asset('images/splurge.ico') }}" />

