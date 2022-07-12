<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
   @include('layouts.header_includes')
   <title>
    {{ $title ?? '' }}
     | {{ config('app.name') }} Events</title>
        <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Italiana&family=Italianno&family=Itim&family=Poppins:ital,wght@0,200;0,400;0,500;1,200;1,500&display=swap"
     rel="stylesheet" />

     <script src="https://kit.fontawesome.com/a076d05399.js"></script>
     
     <link rel="stylesheet" href="{{ asset(mix('css/app.css')) }}" />
     @stack('header_includes')
</head>
<body class="bg-white splurge">
  <x-flash-grabber></x-flash-grabber>
    <div class="min-h-full">
        @include('partials.navbar')
        <main>
          <div class="w-full">
            <!-- Replace with your content -->
            {{ $slot }}
            <!-- /End replace -->
            @include('partials.footer')
          </div>
        </main>
      </div>
      @foreach (['manifest', 'vendor', 'app'] as $name)
      <script nonce="{{ csp_nonce() }}" src="{{ asset(mix(sprintf('js/%s.js', $name))) }}"></script>
      @endforeach

      @stack('scripts')
      @if (false)
      <script src="https://apps.elfsight.com/p/platform.js" defer async></script>
      <div class="elfsight-app-6bd84fdd-7b06-4127-8cfc-f4d68a37be7c"></div>
      @endif
</body>
</html>