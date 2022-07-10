@extends('layouts.default', ['navbar_class' => 'overbanner'])
@php
$subTitleClass = 'text-xl font-bold text-splarge-700 mt-4';
@endphp

@section('title')
    About Us
@endsection

@section('content')
    @include('partials.landing.slides')
    <section id="wedding" class="container mx-auto pt-8">
        <h3 class="text-2xl font-bold text-center mb-4">About {{ config('app.name') }} Events</h3>

        <div class="md:flex flex-row">
            <div class="md:w-1/2">
                <img class="block" src="{{ asset('images/about.png') }}" alt="About Image" />
                <p class="hidden md:block text-center">
                    <a href="{{ route('services.index') }}" class="text-2xl bg-splarge-600 px-6 rounded-lg shadow-md py-2 hover:bg-slate-800 animate-bounce text-white">
                        Checkout our packages!
                    </a>
                </p>
            </div>
            <div class="md:w-1/2">
                <h4 class="{{ $subTitleClass }}">Mission Statement</h4>
                <p>
                    To make every event uniquely memorable
                    and emotive through creative ideas
                    delivered with precision while making the
                    process pleasurable and hassle free.
                </p>
                <h4 class="{{ $subTitleClass }}">
                    Vision
                </h4>

                <p>
                    Our vision to ensure clients satisfaction
                    and loyalty, be the planner of choice
                    when it comes to emotive experiences..
                </p>
                <h4 class="{{ $subTitleClass }}">
                    Core Values
                </h4>
                <ul class="leading-8">
                    <li>
                        Customer service
                    </li>
                    <li>
                        Integrity
                    </li>
                    <li>
                        Teamwork
                    </li>
                    <li>
                        Creativity
                    </li>
                    <li>
                        Respect
                        Relationship
                    </li>
                </ul>

                <div class="mt-4 p-4 bg-slate-900 text-white rounded-md">
                    <h4 class="text-xl font-bold my-4">OUR EXPERIENCE</h4>
                    <ul class="experience-list">
                        <li>
                            <span class="text-splurge-500 mr-8">
                                &#x27A4;
                            </span>
                            <span>
                                We are an event company that has been in existence since
                                2014,with a combined experience of over 10 years in event
                                planning
                            </span>
                        </li>
                        <li>
                            <span class="text-splurge-500 mr-8">
                                &#x27A4;
                            </span>
                            <span>
                                We have very good experience in handling events especially
                                in the Country since we have a good understanding of the
                                various issues that might arise with vendors and other
                                stakeholders
                            </span>
                        </li>
                        <li>
                            <span class="text-splurge-500 mr-8">
                                &#x27A4;
                            </span>
                            <span>
                                We pride ourselves with our attention to details and our ability
                                to properly translate your ideas into reality.
                            </span>
                        </li>
                        <li>
                            <span class="text-splurge-500 mr-8">
                                &#x27A4;
                            </span>
                            <span>
                                We urge you to relax as we take your celebration from
                                conception to reality
                            </span>
                        </li>
                        <li>
                            <span class="text-splurge-500 mr-8">
                                &#x27A4;
                            </span>
                            <span>
                                We do not hands off until the last vendor has delivered.
                            </span>
                        </li>
                        <li>
                            <span class="text-splurge-500 mr-8">
                                &#x27A4;
                            </span>
                            Like our by-line says, we deliver Unforgettable, Emotive
                            Experience
                        </li>
                        <li>
                            <span class="text-splurge-500 mr-8">
                                &#x27A4;
                            </span>
                            <span>
                                Any of Our assigned event consultants: Kemi Okezie, Chinasa
                                Njoku and Itunu Farotimi will be on ground between the hours
                                of 9am to 5pm (Nigerian time) to attend to your needs and
                                questions from now till the event ends.
                            </span>

                        </li>
                    </ul>
                </div>

            </div>
        </div>

        <div class="text-center my-4 block md:hidden">
            <a href="{{ route('services.index') }}" class="text-2xl bg-splarge-600 px-6 rounded-lg shadow-md py-2 hover:bg-slate-800 animate-bounce text-white">
                Checkout our packages!
            </a>
        </div>


        <x-widgets.other-posts title="Recent Events" :post_id="-1"></x-widgets.other-posts>
        <x-widgets.recent-gallery></x-widgets.recent-gallery>
        <p class="mb-8"></p>

    </section>



@endsection
