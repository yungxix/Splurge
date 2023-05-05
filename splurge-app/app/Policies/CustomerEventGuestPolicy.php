<?php

namespace App\Policies;

use App\Models\CustomerEventGuest;
use App\Models\User;
use App\Support\RoleQuery;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerEventGuestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $this->of($user)->canView();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerEventGuest  $customerEventGuest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, CustomerEventGuest $customerEventGuest)
    {
        return $this->of($user)->canView();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $this->of($user)->canCreate();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerEventGuest  $customerEventGuest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, CustomerEventGuest $customerEventGuest)
    {
        return $this->of($user)->canEdit();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerEventGuest  $customerEventGuest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, CustomerEventGuest $customerEventGuest)
    {
        return $this->of($user)->canDelete();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerEventGuest  $customerEventGuest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, CustomerEventGuest $customerEventGuest)
    {
        return $this->of($user)->canDelete();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerEventGuest  $customerEventGuest
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, CustomerEventGuest $customerEventGuest)
    {
        return $this->of($user)->canDelete();
    }

    public function of(User $user) {
        return new RoleQuery($user, CustomerEventGuest::class);
    } 
}
