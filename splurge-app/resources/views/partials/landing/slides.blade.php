
  <section class="home relative w-full">
    <video autoplay muted playsinline loop>
      @php
        $files = ['mp4' => 'videos/res.mp4', 'ogg' => 'videos/res.ogg'];
      @endphp
      @foreach ($files as $type => $file)
        <source src="{{ asset($file) }}" type="video/{{ $type }}" />  
      @endforeach
      Video tag is not supported in this browser.
    </video>
    <div class="content">
      <div class="h-full w-full relative lg:pl-24 md:pl-12 pl-8 flex flex-col justify-center">
        <div class="mt-4 md:mt-2">
          <h1>Serene 
            <br><span>World class Experience</span></h1>
             <a  href="{{ route('services.index') }}">Explore More</a>
        </div>
      </div>
    </div>
  </section>