<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VenueTable extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address_id', 'capacity'];

    public function location() {
        return $this->belongsTo(Address::class, 'address_id');
    }
}
