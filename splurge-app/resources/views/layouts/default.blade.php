<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
    <meta name="description" content="EVENTS HUB IN AFRICA" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/splurge.ico') }}" />
    <title>
        @section('title')
            {{ config('app.name') }}
        @show
         | EVENTS HUB IN AFRICA</title>

        <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Italiana&family=Italianno&family=Itim&family=Poppins:ital,wght@0,200;0,400;0,500;1,200;1,500&display=swap"
     rel="stylesheet" />
     
     <script src="https://kit.fontawesome.com/a076d05399.js"></script>
     <link rel="stylesheet" href="{{ asset(mix('css/app.css')) }}" />

     @stack('header_includes')
</head>
<body class="bg-white splurge @yield('body_class')">
  <x-flash-grabber></x-flash-grabber>
  @include('partials.navbar', ['class' => isset($navbar_class) ? $navbar_class : ''])
    <div class="min-h-full">
        <main>
          <div class="w-full">
            <!-- Replace with your content -->
            @yield('content')
            <!-- /End replace -->
            @include('partials.footer')
          </div>
        </main>
      </div>
      @foreach (['manifest', 'vendor', 'app'] as $name)
      <script src="{{ asset(mix(sprintf('js/%s.js', $name))) }}"></script>
      @endforeach

      @stack('scripts')
      @if (false)
      <script src="https://apps.elfsight.com/p/platform.js" defer async></script>
      <div class="elfsight-app-6bd84fdd-7b06-4127-8cfc-f4d68a37be7c"></div>
      @endif
</body>
</html>