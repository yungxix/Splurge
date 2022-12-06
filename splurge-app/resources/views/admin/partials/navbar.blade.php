@php
use Illuminate\Support\Arr;
use Illuminate\Support\Str;


$items = [
    [
        'text' => 'Administration',
        'url' => route("admin.admin_dashboard"),
        'active' => Request::is('admin/dashboard')
    ],
    [
        'text' => 'Posts / Events',
        'url' => route('admin.posts.index'),
        'active' => Request::is('admin/posts*') || Request::is('admin/events*')
    ],
    [
        'text' => 'Gallery',
        'url' => url('/admin/gallery'),
        'active' => Request::is('admin/gallery*')
    ],
    [
        'text' => 'Services',
        'url' => url('/admin/services'),
        'active' => Request::is('admin/services*')
    ],
    [
        'text' => 'Business',
        'url' => '#',
        'active' => collect(['admin/bookings*', '/admin/customers*', '/admin/messages*'])->contains(fn ($v, $k) => Request::is($v)),
        'items' => [
            [
                'text' => 'Bookings',
                'url' => route('admin.bookings.index')
            ],
            [
                'text' => 'Customer Events',
                'url' => route('admin.customer_events.index')
            ],
            [
                'text' => 'Messages',
                'url' => route('admin.messages.index')
            ]
        ]
    ],
    [
        'text' => 'Public Page',
        'url' => url('/'),
        'active' => Request::is('/')
    ]
];
@endphp

<nav id="navbar">
    
</nav>

@push('scripts')
    <script   nonce="{{ csp_nonce() }}">
    Splurge.navbar.render(document.querySelector('#navbar'), {
        items: {{ Js::from($items) }},
        logo: '{{ asset('/images/v2/splurge.png') }}',
        logoUrl: '{{ url('/') }}',
        authenticated: {{ Auth::check() ? 'true' : 'false'  }}, {{-- I am doing the check this way because it returns an empty value when no logged in user--}}
        username: "{{ Auth::check() ?  Auth::user()->name : '' }}",
        @can('admin')
        userDropdownItems: [
            {
                text: 'Manage tags',
                url: '{{ route('admin.tags.index') }}'
            },
            {
                text: 'Sign out',
                url: '{{ route('logout') }}',
                form: 'POST'
            }
        ],
        @else    
        userDropdownItems: [
            {
                text: 'Sign out',
                url: '{{ route('logout') }}',
                form: 'POST'
            }
        ],
        @endcan
        
        loginUrl: '{{ url('/login') }}'
    })
    </script>
@endpush