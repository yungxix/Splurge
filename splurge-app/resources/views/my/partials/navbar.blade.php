@php
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

$items = [
    [
        'text' => 'Home',
        'url' => route('my.bookings.index'),
        'active' => Request::is('/my/bookings*')
    ],
    [
        'text' => config('app.name'),
        'url' => url('/'),
        'active' => Request::is('/')
    ],
    [
        'text' => 'Events',
        'url' => route('events.index'),
        'active' => Request::is('events/*')
    ],
    [
        'text' => 'Gallery',
        'url' => route('gallery.index'),
        'active' => Request::is('*gallery*')
    ],
    [
        'text' => 'Services',
        'url' => route('services.index'),
        'active' => Request::is('*services*')
    ],
    [
        'text' => 'About Us',
        'url' => url('/about'),
        'active' => Request::is('about')
    ],
    [
        'text' => 'Contact',
        'url' => url('/about/contact'),
        'active' => Request::is('about/contact')
    ]
];

@endphp

<nav id="navbar" class="{{ isset($class) ? $class : "" }}">
    
</nav>

@push('scripts')
    <script  nonce="{{ csp_nonce() }}">
    Splurge.navbar.render(document.querySelector('#navbar'), {
        items: {{ Js::from($items) }},
        logo: '{{ asset('/images/v2/splurge.png') }}',
        logoUrl: '{{ route('my.bookings.index') }}',
        authenticated: {{ Auth::check() ? 'true' : 'false'  }}, {{-- I am doing the check this way because it returns an empty value when no logged in user--}}
        username: "{{ Auth::check() ?  Auth::user()->name : '' }}",
        userDropdownItems: [
            {
                text: 'Sign out',
                url: '{{ route('access.destroy') }}',
                form: 'POST'
            }
        ],
        loginUrl: '{{ url('/login') }}'
    })
    </script>
@endpush