<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory, HasCode;

    protected $fillable = ['amount', 'statement', 'code'];

    public function booking() {
        return $this->belongsTo(Booking::class);
    }
}
