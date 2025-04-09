<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceTier extends Model
{
    use HasFactory, HasCode;

    protected $fillable = ['service_id', 'options', 'code',
     'description',
      'options', 'price',
       'footer_message', 'name', "position", "image_url"];


    protected $casts = ['options' => 'array'];   

    public function service() {
        return $this->belongsTo(Service::class);
    }

    public function synchronizePositions() {
        $others = ServiceTier::where("service_id", $this->service_id)
        ->where("id", "<>", $this->id)
        ->select("id", "position")
        ->orderBy("position", "asc")->get();

        $current_position = $this->position;
        foreach ($others as $tier) {
            if ($tier->position == $current_position) {
                ServiceTier::where("id", $tier->id)->update(['position', $current_position + 1]);
                $current_position += 1;
            }
        }

    }
}
