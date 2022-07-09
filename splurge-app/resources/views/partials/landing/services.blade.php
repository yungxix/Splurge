
@php
  use Illuminate\Support\Str;
@endphp

      <section id="service" class="container mx-auto pt-5 pb-40">
        <div class="mb-8 md:mb-20 text-center">
            <h3 class="mb-2 text-xl">LUXURY WEDDING PLANNERS & PARTY ORGANISERS</h3>
            <p class="text-lg">Make every event uniquely memorable 
              and emotive through creative ideas 
              delivered with precision while making the 
              process pleasurable and hassle free</p>
        </div>
        

        <div class="mx-auto md:w-2/3">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-2 md:gap-4">
            @foreach ($services as $service)
            <div class="single-service">
              <a href="{{ route('services.show', $service) }}?events=1">
                <img src="{{ asset($service['image_url']) }}" alt="{{ $service['name'] }}" />
                <div class="overlay"></div>
                <div class="service-desc">
                    <h4>{{ $service['name'] }}</h4>
                    <hr>
                    <p>
                      {{ Str::limit($service['description'], 120, '...') }}
                    </p>
                </div>
              </a>
            </div>
            @endforeach
  
            <div class="single-service">
              <a href="{{ url('/book')}}">
                <img  class="smx"  src="{{ asset('images/w8.jpeg')}}" width="400px" height="300px" alt="">
                <div class="overlay"></div>
                <div class="service-desc">
                  <h4>BOOK US</h4>
                  <hr>
                  <p>To make every event uniquely memorable 
                    and emotive through creative ideas 
                    delivered with precision while making the 
                    process pleasurable and hassle free.</p>
                </div>     
              </a>
            </div>
  
            <div class="single-service">
              <a href="{{ url('/about') }}">
              <img src="{{ asset('images/w9.jpeg')}}" class="smx" alt="" width="400px" height="300px">
              <div class="overlay"></div>
              <div class="service-desc">
                <h4>Want to know more?</h4>
                <hr />
                <p>
                  Our vision to ensure clients satisfaction 
                  and loyalty, be the planner of choice 
                  when it comes to emotive experiences...
                </p>
                </a>       
            </div>

        </div>


       


        
    </section>