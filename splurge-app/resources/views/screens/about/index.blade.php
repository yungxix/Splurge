@extends(config('view.defaults.layout', ['title' => 'About']))


@section('content')
  @include('partials.landing.slides')
  <section id="wedding" class="container mx-auto pt-8">
            <h3 class="text-2xl font-bold text-center mb-4">About SerenSplurge Event</h3>
            <p class="text-justify mb-4"> 
                Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quisquam perferendis hic quasi, voluptas, neque molestias inventore dolore eveniet doloribus rerum nemo. Nemo incidunt nihil possimus officiis reiciendis, 
                porro, vitae unde explicabo, alias quia debitis ab! Accusamus possimus iusto molestiae dicta.
            </p>
            <div class="md:flex flex-row">
                <div class="md:w-2/3 md:pr-4">
                    @include('partials.contact')
                    <hr class="mt-4 md:mt-8 bg-pink-500 h-px text-pink-300" />
                    @if (true)
                    <div class="mt-4 md:mt-10">
                        <div class="flex flex-row gap-x-4 justify-between items-center">
                            <div>
                                <img src="{{ asset('images/p4.jpeg') }}" alt="">
                                <p class="text-red-800 md:text-lg font-semibold text-center">Eloquent Events</p>
                            </div>
                            <div>
                                <img src="{{ asset('images/l1.jpg') }}" alt="">
                                <p class="text-red-800 md:text-lg font-semibold text-center">Memorable Momments</p>
                            </div>
                            <div>
                                <img src="{{ asset('images/q2.jpeg') }}" alt="">
                                <p class="text-red-800 md:text-lg font-semibold text-center">Luxurious Weddings</p>
                            </div>      
                        </div>
                        <p class="mb-8"></p>
                    </div>
                    @endif
                    @include('partials.map')
                </div>
                <div class="md:w-1/3 bg-gray-300 p-4 md:rounded-md">
                    <x-widgets.other-posts title="Recent Events" :post_id="-1"></x-widgets.other-posts>
                    <x-widgets.recent-gallery></x-widgets.recent-gallery>
                </div>
            </div>
            <p class="mb-8"></p>
           
  </section>



@endsection