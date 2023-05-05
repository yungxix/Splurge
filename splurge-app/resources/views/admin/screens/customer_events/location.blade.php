@if (!is_null($model->booking) && !is_null($model->booking->location))
<address>
    <em class="block">
        {{ $model->booking->location->line1 }}
    </em>
    @unless (empty($model->booking->line2))
    <em class="block">
        {{ $model->booking->location->line2 }}
    </em>    
    @endunless
    <em class="block">
        {{$model->booking->location->state}}
    </em>
</address>
@endif