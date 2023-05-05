<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserRequest extends FormRequest
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
        if ($this->isMethod('PATCH') || $this->isMethod('PUT')) {
            return [
                'name' => 'sometimes|max:120',
                'roles' => 'nullable|array',
                
            ];    
        }
        return [
            'name' => 'required|max:120',
            'email' =>  [
                'required',
                sprintf('unique:%s,%s', (new User())->getTable(), 'email')
            ],
            'roles' => 'required|array',
            'password' => 'required|max:24|min:6'

        ];
    }

    public function store() {
        return DB::transaction(function () {
            $source = $this->safe();
            $data = $source->only(['name', 'email', 'password']);
            $user = User::create(array_merge($data, [
                'password' => Hash::make($data['password']) 
            ]));

            return $this->saveRoles($user, true);
        });
        
    }

    private function saveRoles(User $user, bool $isNew): User {
        $roles = $this->input('roles');
        if (is_null($roles)) {
            return $user;
        }
        if (!$isNew) {
            $user->roles()->delete();
        }
        $role_objects = [];
        foreach ($roles as $role) {
            $role_objects[] = new UserRole(['name' => $role]);
        }
        $user->roles()->saveMany($role_objects);
        return $user;
    }

    public function update(User $user) {
        return DB::transaction(function () use ($user) {
            $data = $this->safe(['name']);
            $user->update($data);
            return $this->saveRoles($user, false);
        });
        
        
    }
}
