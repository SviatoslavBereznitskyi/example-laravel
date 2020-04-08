<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Contact;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the profile vaccination.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Contact $contact
     * @return mixed
     */
    public function view(User $user, Contact $contact)
    {
        return $this->isBelongsToUser($contact, $user);
    }

    /**
     * Determine whether the user can create profile vaccinations.
     *
     * @param  \App\Models\User $user
     * @param int $profileId
     * @return mixed
     */
    public function create(User $user, int $profileId)
    {
        return $user->profiles()->where('id', $profileId)->exists();
    }

    /**
     * Determine whether the user can update the profile vaccination.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Contact $contact
     * @return mixed
     */
    public function update(User $user, Contact $contact)
    {
        return $this->isBelongsToUser($contact, $user);
    }

    /**
     * Determine whether the user can delete the profile vaccination.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Contact $contact
     * @return mixed
     */
    public function delete(User $user, Contact $contact)
    {
        return $this->isBelongsToUser($contact, $user);
    }

    /**
     * Determine whether the user can restore the profile vaccination.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Contact $contact
     * @return mixed
     */
    public function restore(User $user, Contact $contact)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the profile vaccination.
     *
     * @param  \App\Models\User $user
     * @param  \App\Models\Contact $contact
     * @return mixed
     */
    public function forceDelete(User $user, Contact $contact)
    {
        //
    }

    private function isBelongsToUser(Contact $contact, User $user)
    {
        return $contact->profile->user->is($user);
    }
}
