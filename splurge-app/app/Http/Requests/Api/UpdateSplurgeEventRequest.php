<?php

namespace App\Http\Requests\Api;

use App\Models\SplurgeEvent;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSplurgeEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user('api')->can('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['sometimes', 'max:255'],
            'event_date' => ['sometimes', 'date'],
            'service_tier_id' => ['sometimes', 'integer'],
            'description' => ['sometimes', 'max:500']
        ];
    }

    public function commit(SplurgeEvent $event) {
        $event->updateOrFail($this->validated());
    }
}
