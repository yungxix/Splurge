@php
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\Post;
use App\Support\HtmlHelper;
use App\Support\ServiceSupport;

$has_posts =  !is_null(Post::where("id", ">", 0)->selectRaw("1")->first());



$items = [
    [
        'text' => 'Home',
        'url' => url('/'),
        'active' => Request::is('/')
    ],
    $has_posts ? [
        'text' => 'Events',
        'url' => route('events.index'),
        'active' => Request::is('events/*')
    ] : null,
    [
        'text' => 'Gallery',
        'url' => route('gallery.index'),
        'active' => Request::is('*gallery*')
    ],
    ServiceSupport::hideServicesMenuItem() ? (isset($services) ? 'services_menu' : null) :
    [
        'text' => 'Our Services',
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

// if (isset($services)) {
//     $items = HtmlHelper::insertServiceLinks($items, $services);
// }



if (Auth::check()) {
    if (Auth::user()->can("admin")) {
        $items[] = [
            'url' => route("admin.admin_dashboard"),
            'text' => 'Administration',
            'active' => Request::is('admin/dashboard*')
        ];
    } else {
        $items[] = [
            'url' => url('/dashboard'),
            'text' => 'My Dashboard',
            'active' => Request::is('dashboard*')
        ];
    }
    
}
@endphp

<nav id="navbar" class="{{ isset($class) ? $class : "" }}">
    
</nav>

@push('scripts')
    <script  nonce="{{ csp_nonce() }}">
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