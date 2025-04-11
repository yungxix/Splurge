<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignedVenueTable extends Model
{
    use HasFactory;

    protected $fillable = ['table_id', 'event_user_id'];

    public function guest() {
        return $this->belongsTo(SplurgeEventUser::class, 'event_user_id');
    }

    public function venueTable() {
        return $this->belongsTo(VenueTable::class, 'table_id');
    }
}
