
@php
  use Carbon\Carbon;
  use Illuminate\Support\Arr;
@endphp
<footer id="footer">
  <div class="title-footer mb-4">
      <h4>SERENE SPLURGE EVENTS</h4>
  </div>

  <div class="fox">
    
      <p class="text-sm"> <a href="#">Contacts</a> / <a href="https://instagram.co"> Teams </a> / <a>About Us</a> / <a>Terms</a></p>
    
  </div>
  <hr />
  <div class="container mx-auto">
    <div class="flex justify-between text-white">
      <span>
        @php
          $follow_items = [
              [
                'url' => '#',
                'icon_class' => 'fab fa-facebook'
              ], [
                'url' => '#',
                'icon_class' => 'fab fa-twitter'
              ], [
                  'url' => '#',
                  'icon_class' => 'fab fa-instagram-square'
              ], [
                'url' => '#',
                'icon_class' => 'fab fa-youtube',
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
              <i class="{{ $item['icon_class'] }}"></i>
            </a>
          @endforeach
      </span>
      
      <em>Copyrights {{ Carbon::now()->format('Y') }} SerenSplurge Events. All right reserved</em>
    </div>
  </div>
 
</footer>
