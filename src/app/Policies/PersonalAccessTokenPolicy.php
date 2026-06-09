<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PersonalAccessToken;
use Illuminate\Auth\Access\HandlesAuthorization;

class PersonalAccessTokenPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PersonalAccessToken');
    }

    public function view(AuthUser $authUser, PersonalAccessToken $personalAccessToken): bool
    {
        return $authUser->can('View:PersonalAccessToken');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PersonalAccessToken');
    }

    public function update(AuthUser $authUser, PersonalAccessToken $personalAccessToken): bool
    {
        return $authUser->can('Update:PersonalAccessToken');
    }

    public function delete(AuthUser $authUser, PersonalAccessToken $personalAccessToken): bool
    {
        return $authUser->can('Delete:PersonalAccessToken');
    }

}