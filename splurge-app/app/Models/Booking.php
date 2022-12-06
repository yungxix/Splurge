<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Booking extends Model
{
    use HasFactory, HasCode, HasCustomerEvent;

    protected $casts = ['event_date' => 'datetime'];

    protected $fillable = ['event_date', 'service_tier_id', 'current_charge', 'description', 'customer_id'];

    public function customer() {
            return $this->belongsTo(Customer::class);   
    }

    public function customerEvent() {
        return $this->hasOne(CustomerEvent::class);
    }

    public function serviceTier() {
        return $this->belongsTo(ServiceTier::class, 'service_tier_id');
    }

    public function location() {
        return $this->morphOne(Address::class, "addressable");
    }

    public function payments() {
        return $this->hasMany(Payment::class);
    }

    public function accessTokens() {
        return $this->morphMany(SplurgeAccessToken::class, "access");
    }

    public function hasPayments($stats): bool {
        return $stats->contains(function ($item) {
            return Arr::get($item, 'booking_id') == $this->id && Arr::get($item, 'paid', 0) > 0;
        });
    }

    public function messages() {
        return $this->morphMany(Communication::class, "channel");
    }

    public function hasFullyPaid($stats): bool {
        return $stats->contains(function ($item) {
            return Arr::get($item, 'booking_id') == $this->id && Arr::get($item, 'paid', 0) >= $this->current_charge;
        });
    }

    public function getRemainingBalance($payments) {
        if ($this->current_charge < 1) {
            return 0;
        }
        $item = $payments->first(fn ($i, $j) => Arr::get($i, "booking_id") == $this->id);
        if (is_null($item)) {
            return 0;
        }
        
        return $this->current_charge = Arr::get($item, 'paid', 0);
    }
}
