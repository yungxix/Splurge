<?php

namespace App\Http\Requests;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\ServiceTier;
use App\Support\UploadProcessor;
use Illuminate\Support\Facades\DB;

class ServiceTierRequest extends FormRequest
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
            'name' => 'required|max:200',
            'price' => 'nullable|numeric',
            'description' => 'required|max:255',
            'options' => 'nullable|array',
            'footer_message' => 'nullable|max:255',
            "position" => "nullable|integer",
            "image" => "nullable|mimes:png,jpg,jpeg|max:" . (4 * 1024)
        ];
    }


    public function createItem(Service $service): ServiceTier {
        $data = $this->safe()->except('image');

        if ($this->hasFile('image')) {
            $processor = new UploadProcessor($this, 'images/services', 'image', FALSE, 'image_url');
            if ($result = $processor->handle()) {
                $data = array_merge($data, $result);
            }
        }

        $tier = new ServiceTier($data);
        $tier->code = $tier->generateCode();
        if (is_null($tier->position) || $tier->position === -1) {
            $tier->position = self::nextPosition($service);
        }
        $service->tiers()->save($tier);
        return $tier;
    }

    public function updateItem(ServiceTier $tier): ServiceTier {
        return DB::transaction(function () use ($tier) {
            $data = $this->safe()->except('image');
            
            if ($this->hasFile('image')) {
                $processor = new UploadProcessor($this, 'images/services', 'image', FALSE, 'image_url');
                if ($result = $processor->handle()) {
                    $data = array_merge($data, $result);
                }
            }
            
            $tier->update($data);

            if ($this->has("position") && self::hasPosition($tier->service_id, $this->input("position"), $tier->id)) {
                $tier->synchronizePositions();
            }
            return $tier;
        });
        
    }


    private static function nextPosition(Service $service) {
        $items = $service->tiers()->select("position")
            ->orderBy("position", "desc")->limit(1)->get();

        if ($items->isEmpty()) {
            return 1;
        }   
        return $items->last()->position + 1;

    }

    private static function hasPosition($serviceId, $position, $omitId = NULL): bool {
        $query = ServiceTier::where([
            "service_id" => $serviceId,
             "position" => $position]);

        if (!is_null($omitId)) {
            $query = $query->where("id", "<>", $omitId);
        }     

       $record = $query->selectRaw("1")->limit(1)->qet();

       return !$record->isEmpty();
    }

}
