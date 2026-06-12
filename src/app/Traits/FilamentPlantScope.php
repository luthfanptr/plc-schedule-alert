<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait FilamentPlantScope
{
    protected function plantScope(Builder $query): Builder
    {
        /**@var App\Models\User $user */
        $user = Auth::user();

        if ($user && ! $user->hasRole('super_admin')) {
            $assignedPlants = $user->plants->pluck('Name')->toArray();
            $query->whereIn('plant', $assignedPlants);
        }

        return $query;
    }
}
