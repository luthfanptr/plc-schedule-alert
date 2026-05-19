<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PlcStatus;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlcStatusPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PlcStatus');
    }

    public function view(AuthUser $authUser, PlcStatus $plcStatus): bool
    {
        return $authUser->can('View:PlcStatus');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PlcStatus');
    }

    public function update(AuthUser $authUser, PlcStatus $plcStatus): bool
    {
        return $authUser->can('Update:PlcStatus');
    }

    public function delete(AuthUser $authUser, PlcStatus $plcStatus): bool
    {
        return $authUser->can('Delete:PlcStatus');
    }

}