<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;

class UserRepository
{
    public function getTeknisiPlant(string $plant): Collection
    {
        // scope berdasarkan nama role Spatie Permission
        return User::role('teknisi')
            ->whereHas('plants', fn($q) => $q->where('Name', trim($plant)))
            ->get();
    }

    public function getSupervisor(string $plant): Collection
    {
        return User::role('supervisor')
            ->whereHas('plants', fn($q) => $q->where('Name', trim($plant)))
            ->get();
    }
}
