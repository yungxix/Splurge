<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
    <meta name="description" content="EVENTS HUB IN AFRICA" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/splurge.ico') }}" />
    <title>
        @section('title')
            {{ config('app.name') }}
        @show
         | EVENTS HUB IN AFRICA</title>

        <!-- Fonts -->
    <link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
    integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Italiana&family=Italianno&family=Itim&family=Poppins:ital,wght@0,200;0,400;0,500;1,200;1,500&display=swap"
     rel="stylesheet">
     <script src="https://kit.fontawesome.com/a076d05399.js"></script>
     <link rel="stylesheet" href="{{ mix('css/app.css') }}" />

     @stack('header_includes')
</head>
<body class="bg-white splurge @yield('body_class')">
    <x-flash-grabber></x-flash-grabber>
    
    <div class="min-h-full">
        @include('admin.partials.navbar')
        <main>
          <div class="w-full">
            <!-- Replace with your content -->
            @yield('content')
            <!-- /End replace -->
            @include('partials.footer')
          </div>
        </main>
      </div>
      <script src="{{ mix('js/app.js') }}"></script>

      @stack('scripts')

</body>
</html>