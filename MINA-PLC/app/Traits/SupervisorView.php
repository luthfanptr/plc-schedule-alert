<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait SupervisorView
{
    public static function canView(): bool
    {
        /** @var App\Models\User $user */
        $user = Auth::user();
        return $user && ! $user->hasRole('teknisi');
    }
}
