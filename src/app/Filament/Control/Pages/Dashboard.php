<?php

declare(strict_types=1);

namespace App\Filament\Control\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use UnitEnum;

class Dashboard extends BaseDashboard
{
    protected static string|null|UnitEnum $navigationGroup = 'General';
    protected static bool $shouldRegisterNavigation = false;
}
