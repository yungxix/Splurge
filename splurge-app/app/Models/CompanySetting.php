<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use HasFactory;

    protected $fillable = ["contact_email", "contact_phone"];

    public function  locations() {
        return $this->morphMany(Address::class, "addressable");
    }
}
