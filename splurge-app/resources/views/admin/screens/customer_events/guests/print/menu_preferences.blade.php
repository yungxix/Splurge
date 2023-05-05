@if ($guests->menuPreferences->isEmpty())
<p>
    No menu preference specified for this guest
</p>
@else
<ul>
    @foreach ($guest->menuPreferences as $pref)
        <li>{{ $pref->name }}</li>
    @endforeach
</ul>
@endif