<?php

namespace App\Policies;

use App\Models\CustomerEvent;
use App\Models\User;
use App\Support\RoleQuery;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerEventPolicy
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
     * @param  \App\Models\CustomerEvent  $customerEvent
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, CustomerEvent $customerEvent)
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
     * @param  \App\Models\CustomerEvent  $customerEvent
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, CustomerEvent $customerEvent)
    {
        return $this->of($user)->canEdit();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerEvent  $customerEvent
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, CustomerEvent $customerEvent)
    {
        return $this->of($user)->canDelete();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerEvent  $customerEvent
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, CustomerEvent $customerEvent)
    {
        return $this->of($user)->canDelete();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CustomerEvent  $customerEvent
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, CustomerEvent $customerEvent)
    {
        return $this->of($user)->canDelete();
    }

    public function of(User $user) {
        return new RoleQuery($user, CustomerEvent::class);
    }
}
