@php
use Illuminate\Support\Arr;
use Illuminate\Support\Str;


$items = [
    [
        'text' => 'Home',
        'url' => url('/'),
        'active' => Request::is('/')
    ],
    [
        'text' => 'Celebrations',
        'url' => url('/events/celebrations'),
        'active' => Request::is('events/celebrations')
    ],
    [
        'text' => 'Weddings',
        'url' => url('/events/weddings'),
        'active' => Request::is('events/weddings')
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

if (Auth::check()) {
    $items[] = [
        'url' => url('/dashboard'),
        'text' => 'Dashboard',
        'active' => Request::is('dashboard*')
    ];
}
@endphp

<nav id="navbar" class="default">
    
</nav>

@push('scripts')
    <script>
    Splurge.navbar.render(document.querySelector('#navbar'), {
        items: {{ Js::from($items) }},
        logo: '{{ asset('/images/v2/splurge.png') }}',
        logoUrl: '{{ url('/') }}',
        authenticated: {{ Auth::check() ? 'true' : 'false'  }}, {{-- I am doing the check this way because it returns an empty value when no logged in user--}}
        username: "{{ Auth::check() ?  Auth::user()->name : '' }}",
        userDropdownItems: [
            {
                text: 'Sign out',
                url: '{{ route('logout') }}',
                form: 'POST'
            }
        ],
        loginUrl: '{{ url('/login') }}'
    })
    </script>
@endpush