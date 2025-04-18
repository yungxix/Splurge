<?php

namespace App\Http\Requests\Api;

use App\Models\SplurgeEvent;
use App\Support\BarcodeGenerator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class CreateSplurgeEventUserRequest extends FormRequest
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
            'title' => ['sometimes', 'nullable', 'max:50'],
            'first_name' => ['required', 'max:120'],
            'last_name' => ['required', 'max:120'],
            'email' => ['sometimes', 'nullable', 'email'],
            'phone' => ['sometimes', 'nullable', 'max:16'],
            'gender' => ['sometimes', 'nullable', 'max:16']
        ];
    }

    public function commit(SplurgeEvent $event) {
        $tag = sprintf('splurge://events/%s/guests/%s', $event->code, Str::random());
        
        return $event->members()->create(array_merge($this->validated(),
             ['role' => 'GUEST',
              'tag' => $tag,
                'barcode_image_url' => BarcodeGenerator::create($tag)]));
    }
}
