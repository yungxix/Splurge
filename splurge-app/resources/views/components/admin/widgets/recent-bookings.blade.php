<x-admin.widgets.bookings :title="'Recent Bookings'" :bookings="$recent_bookings" :url="$recent_url"></x-admin.widgets.bookings>
@isset($upcoming_bookings)
<x-admin.widgets.bookings :title="'Upcoming Bookings'" :bookings="$upcoming_bookings" :url="$upcoming_url"></x-admin.widgets.bookings>
@endisset