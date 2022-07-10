@props(['message' => 'Sorry. This page is under construction'])
<div>
    <h4 class="text-lg font-bold text-splarge-800 text-center my-8">
        {{$message}}
    </h4>
    <img class="mx-auto w-3/5" src="{{ asset('images/serene_empty_collage.png') }}" alt="Empty image" />
</div>