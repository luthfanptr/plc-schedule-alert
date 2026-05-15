<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PlcData;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlcDataPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PlcData');
    }

    public function view(AuthUser $authUser, PlcData $plcData): bool
    {
        return $authUser->can('View:PlcData');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PlcData');
    }

    public function update(AuthUser $authUser, PlcData $plcData): bool
    {
        // return $authUser->can('Update:PlcData');
        return false;
    }

    public function delete(AuthUser $authUser, PlcData $plcData): bool
    {
        return $authUser->can('Delete:PlcData');
    }

}