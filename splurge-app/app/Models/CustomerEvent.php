<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerEvent extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id', 'name', 'event_date', 'require_guest_confirmation'];

    private $safeDate;

    protected $casts = [
        'event_date' => 'date',
        'require_guest_confirmation' => 'boolean'
    ];

    public function booking() {
        return $this->belongsTo(Booking::class, 'booking_id');
    }

    public function guests() {
        return $this->hasMany(CustomerEventGuest::class);
    }

    public function scopeSearch($builder, $term) {
        return $builder->where('name', 'like', "%$term%");
    }
    public function isPast() {
        return $this->event_date->isBefore(Carbon::now());
    }

    public function safeEventDate() {
        return $this->event_date;
    }

    public function eventTables() {
        return $this->hasMany(EventTable::class);
    }
}
