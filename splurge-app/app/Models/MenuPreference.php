<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuPreference extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'comment', 'guest_id'];

    public function guest() {
        return $this->belongsTo(CustomerEventGuest::class, "guest_id");
    }
}
