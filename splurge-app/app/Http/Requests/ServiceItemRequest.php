<?php

namespace App\Http\Requests;

use App\Models\Service;
use App\Models\ServiceItem;
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
            'required' => 'nullable|boolean',
            'options' => 'nullable|array',
            'description' => 'nullable|max:1000',
            'price' => 'nullable|numeric',
            'pricing_type' => ['nullable', Rule::in(['fixed', 'increment', 'incremental', 'percentage', 'percent'])],
            'category' => 'nullable|max:15',
            'sort_number' => 'nullable|integer'
        ];
    }


    public function addToService(Service $service) {
        $item = $service->items()->create($this->safe()->all());
        return $item;
    }

    public function updateItem(ServiceItem $item) {
        $item->update($this->safe()->all());
        return $item;
    }
}
