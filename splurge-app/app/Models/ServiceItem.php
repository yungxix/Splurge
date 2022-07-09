<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Arr;

class ServiceItem extends Model
{
    use HasFactory;

    protected $casts = ['options' => 'array', 'required', 'boolean'];

    protected $fillable  = ['name', 'price', 'image_url',
     'pricing_type', 'description',
      'required', 'category',
       'options', 'service_id', 'sort_number'];


    public function service() {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function percentageInfo() {
        if (preg_match('/percent/', $this->pricing_type)) {
            $rate = Arr::get($this->options, 'rate');
            if (is_null($rate)) {
                return '';
            }
            return "@ {$rate}%";
        }
        return '';
    }
}
