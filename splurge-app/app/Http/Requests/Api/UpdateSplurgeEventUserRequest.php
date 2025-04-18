<?php

namespace App\Http\Requests\Api;

use App\Models\SplurgeEventUser;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSplurgeEventUserRequest extends FormRequest
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
            'first_name' => ['sometimes', 'max:120'],
            'last_name' => ['sometimes', 'max:120'],
            'email' => ['sometimes', 'nullable', 'email'],
            'phone' => ['sometimes', 'nullable', 'max:16'],
            'gender' => ['sometimes', 'nullable', 'max:16']
        ];
    }

    public function commit(SplurgeEventUser $user) {
       return $user->update($this->validated());
    }
}
