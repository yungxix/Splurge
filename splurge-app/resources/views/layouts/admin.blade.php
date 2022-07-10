<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.header_includes')
    
    <link rel="icon" type="image/x-icon" href="{{ asset('images/splurge.ico') }}" />
    <title>
        @section('title')
            {{ config('app.name') }}
        @show
    </title>

        <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Italiana&family=Italianno&family=Itim&family=Poppins:ital,wght@0,200;0,400;0,500;1,200;1,500&display=swap"
     rel="stylesheet" />
     <script src="https://kit.fontawesome.com/a076d05399.js"></script>
     <link nonce="{{ csp_nonce() }}" rel="stylesheet" href="{{ asset(mix('css/app.css')) }}" />

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
      @foreach (['manifest', 'vendor', 'app'] as $name)
      <script src="{{ asset(mix(sprintf('js/%s.js', $name))) }}"></script>
      @endforeach
      

      @stack('scripts')
</body>
</html>