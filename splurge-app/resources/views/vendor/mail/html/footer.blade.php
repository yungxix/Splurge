
<table>
<tr>
<td>
<table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="content-cell" align="center">
{{ Illuminate\Mail\Markdown::parse($slot) }}
</td>
</tr>
</table>
</td>
</tr>
Thanks<br/>
Warm regards<br/>
{{ config('app.name') }}<br/>

@isset($companySetting)

Email: <a href="mailto:{{$companySetting->contact_email}}">{{$companySetting->contact_email}}</a><br />

Phone: <a href="tel:{{$companySetting->contact_phone}}">{{$companySetting->contact_phone}}</a>
@endisset
