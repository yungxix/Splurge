<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestUserBagItem extends Model
{
    use HasFactory;

    protected $casts = ['confirmed_at' => 'datetime'];

    protected $fillable = ['confirmed_by', 'confirmed_at', 'name', 'item_count', 'item_type', 'event_user_id'];


    public function eventUser() {
        return  $this->belongsTo(SplurgeEventUser::class, 'event_user_id');
    } 
}
