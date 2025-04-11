<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SplurgeEventUser;
use App\Models\Address;
use App\Models\HasCode;

class SplurgeEvent extends Model
{
    use HasFactory;

    use HasCode;

    protected $casts = ['event_date' => 'date', 'require_confirmation_for_guests' => 'boolean'];

    protected $fillable = ['name', 'remote_id', 'description', 'event_date', 'require_confirmation_for_guests','service_tier_id'];


    public function members() {
        return $this->hasMany(SplurgeEventUser::class, 'event_id');
    }

    public function locations() {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function serviceTier() {
        return $this->belongsTo(ServiceTier::class, 'service_tier_id');
    }


}
