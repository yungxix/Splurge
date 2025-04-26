<?php

namespace App\Http\Requests\Api;

use App\Models\SplurgeEventUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class UpdateSplurgeEventUserRequest extends FormRequest
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
            'gender' => ['sometimes', 'nullable', 'max:16'],
            'present_at' => ['sometimes', 'date'],
        ];
    }

    public function commit(SplurgeEventUser $user) {
        $validated = $this->validated();
        $safe_attributes = Arr::except($validated, 'present_at');
        $user->fill($safe_attributes);
        if ($validated['present_at'] && is_null($user->present_at)) {
            $user->present_at = $validated['present_at'];
        }
       $user->saveOrFail();
       return $user;
    }
}
