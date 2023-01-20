<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTable extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'capactity', 'customer_event_id'];

    private $available;

    private $availabilitySet = false;


    public function customerEvent() {
        return $this->belongsTo(CustomerEvent::class, 'customer_event_id');
    }

    /**
     * Get the value of available
     */ 
    public function getAvailable()
    {
        return $this->available;
    }

    /**
     * Set the value of available
     *
     * @return  self
     */ 
    public function setAvailable($available)
    {
        $this->available = $available;

        $this->availabilitySet;

        return $this;
    }

    public function isAvailabilitySet(): bool {
        return $this->availabilitySet == true;
    }
}
