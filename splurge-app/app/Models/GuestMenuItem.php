<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestMenuItem extends Model
{
    use HasFactory;

    protected $fillable = ['event_user_id', 'menu_item_id'];

    public function menuItem() {
        return $this->belongsTo(MenuItem::class, 'menu_item_id');
    }

    public function eventUser() {
        return $this->belongsTo(SplurgeEventUser::class, 'event_user_id');
    }
}
