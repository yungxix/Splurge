
@php
  use Illuminate\Support\Str;
@endphp

      <section id="service" class="container mx-auto pt-5 pb-40">
        <div class="title-text">
            <h3>INTERNATIONAL LUXURY WEDDING PLANNERS & PARTY PRODUCERS</h3>
            <p>We Create Exceptional Memories</p>
        </div>


        <div class="mx-auto md:w-2/3">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-2 md:gap-4">
            @foreach ($services as $service)
            <div class="single-service">
              <img src="{{ asset($service['image_url']) }}" alt="{{ $service['name'] }}" />
              <div class="overlay"></div>
              <div class="service-desc">
                  <h4>{{ $service['name'] }}</h4>
                  <hr>
                  <p>
                    {{ Str::limit($service['description'], 120, '...') }}
                  </p>
              </div>
            </div>
            @endforeach
  
            <div class="single-service">
              <a href="{{ url('/book')}}">
                <img  class="smx"  src="{{ asset('images/w8.jpeg')}}" width="400px" height="300px" alt="">
                <div class="overlay"></div>
                <div class="service-desc">
                  <h4>BOOK US</h4>
                  <hr>
                  <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sit obcaecati voluptatum facere architecto
                      vero minima delectus nam saepe eligendi placeat?</p>
                </div>     
              </a>
            </div>
  
            <div class="single-service">
              <a href="{{ url('/about') }}">
              <img src="{{ asset('images/w9.jpeg')}}" class="smx" alt="" width="400px" height="300px">
              <div class="overlay"></div>
              <div class="service-desc">
                <h4>Want to know more?</h4>
                <hr>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sit obcaecati voluptatum facere architecto
                     vero minima delectus nam saepe eligendi placeat?</p>
                </a>       
            </div>

        </div>


       


        
    </section>