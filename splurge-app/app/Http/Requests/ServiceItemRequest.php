<?php

namespace App\Http\Requests;

use App\Models\Service;
use App\Models\ServiceItem;
use App\Support\UploadProcessor;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class ServiceItemRequest extends FormRequest
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
            'name' => 'required|max:255',
            'required' => 'sometimes|nullable|boolean',
            'options' => 'sometimes|nullable|array',
            'description' => 'sometimes|nullable|max:1000',
            'price' => 'sometimes|nullable|numeric',
            'pricing_type' => ['sometimes', 'nullable', Rule::in(['fixed', 'increment', 'incremental', 'percentage', 'percent'])],
            'category' => 'sometimes|nullable|max:15',
            'sort_number' => 'sometimes|integer',
            "image" => "sometimes|mimes:png,jpg,jpeg|max:"  . (4 * 1024)
        ];
    }


    public function addToService(Service $service) {
        $data = $this->safe()->except('image');

        if ($this->hasFile('image')) {
            $processor = new UploadProcessor($this, 'images/services', 'image');
            if ($result = $processor->handle()) {
                $data = array_merge($data, $result);
            }
        }

        $item = $service->items()->create($data);
        return $item;
    }

    public function updateItem(ServiceItem $item) {
        $data = $this->safe()->except('image');
        if ($this->hasFile('image')) {
            $processor = new UploadProcessor($this, 'images/services', 'image');
            if ($result = $processor->handle()) {
                $data = array_merge($data, $result);
            }
        }
        $item->update($data);
        return $item;
    }
}
