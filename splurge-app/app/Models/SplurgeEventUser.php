<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SplurgeEventUser extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'last_name', 'barcode_image_url', 'gender', 'tag', 'title', 'customer_relationship', 'role', 'remote_id'];

    public function splurgeEvent() {
        return $this->belongsTo(SplurgeEvent::class, 'event_id');
    }

    public function tables() {
        return $this->hasMany(AssignedVenueTable::class, 'event_user_id');
    }

    public function menuItems() {
        return $this->hasMany(GuestMenuItem::class, 'event_user_id');
    }

    public function bagItems() {
        return $this->hasMany(GuestUserBagItem::class, 'event_user_id');
    }
}
