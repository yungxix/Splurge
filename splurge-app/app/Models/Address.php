<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = ['line1', 'line2', 'name', 'state', 'zip', 'latitude',
     'longitude', 'country', 'addressable_type', 'addressable_id'];

    public function addressable() {
        return $this->morphTo("addressable");
    }
}
