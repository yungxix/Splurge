<?php

namespace App\Http\Requests;

use App\Models\ServiceTier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class ServiceTierPositionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'position' => 'required|integer'
        ];
    }


    public function applyTo(ServiceTier $serviceTier) {
        return DB::transaction(function () use ($serviceTier) {
            $old_position = $serviceTier->position;
        
            $position = $this->input("position");

            $replace_tier = ServiceTier::where(['position' => $position, 'service_id' => $serviceTier->service_id])->first();

            

            $serviceTier->position = $position;
            $serviceTier->save();
            if (!is_null($replace_tier)) {
                $replace_tier->position = $old_position;
                $replace_tier->save();
            } else {
                $serviceTier->synchronizePositions();
            }
        });
        


    }

}
