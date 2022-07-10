
@php
  use Carbon\Carbon;
  use Illuminate\Support\Arr;
@endphp
<footer id="footer">
  <div class="title-footer mb-4">
      <h4 class="uppercase">{{config('app.name')}} EVENTS</h4>
  </div>

  <div class="fox">
    
      <p class="text-sm"> <a href="#">Contacts</a> / <a href="#"> Teams </a> / <a href="/about">About Us</a> / <a href="#">Terms</a></p>
    
  </div>
  <hr class="my-8" />
  <div class="container mx-auto">
    <div class="md:grid grid-cols-2  text-white">
      <p class="text-center md:text-left social-links">
        @php
          $follow_items = [
              [
                'url' => '#',
                'view' => 'svgs.logo-facebook',
                'icon_class' => 'fa fa-facebook',
                'icon_alt' => 'Facebook link icon'
              ], [
                'url' => '#',
                'view' => 'svgs.logo-twitter',
                'icon_class' => 'fa fa-twitter',
                'icon_alt' => 'Twitter link icon'
              ], [
                  'url' => '#',
                  'view' => 'svgs.logo-instagram',
                  'icon_class' => 'fa fa-instagram-square',
                  'icon_alt' => 'Instagram link icon'
              ], [
                'url' => '#',
                'view' => 'svgs.logo-youtubue',
                'icon_class' => 'fa fa-youtube',
                'icon_alt' => 'YouTube page link icon',
                'available' => false
              ] 
          ];


        @endphp
        <strong>Give us a follow</strong>
          @foreach ($follow_items as $item)
            @if (!Arr::get($item, 'available', true))
              @continue
            @endif
            <a href="{{ $item['url'] }}" class="ml-8 duration-150 hover:-translate-y-1 inline-block">
              @if (isset($item['view']))
                @include($item['view'])
              @elseif (isset($item['icon_url']))
                <img class="w-4" src="{{ asset($item['icon_url']) }}" alt="{{ Arr::get($item, 'icon_alt', '') }}" />
              @else
              <i class="{{ $item['icon_class'] }}"></i>  
              @endif
              
            </a>
          @endforeach
      </p>
      <p class="text-center pt-4 md:pt-0 md:text-right">
        <em>Copyright {{ Carbon::now()->format('Y') }} {{config('app.name')}} Events. All rights reserved</em>
      </p>
      
    </div>
  </div>
 
</footer>

