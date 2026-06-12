<?php

declare(strict_types=1);

namespace App\Filament\Control\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class Dashboard extends BaseDashboard
{
    protected static string|null|UnitEnum $navigationGroup = 'General';
    protected static bool $shouldRegisterNavigation = false;

    use HasFiltersForm;

    public function getColumns(): int|array
    {
        return [
            'md' => 2,
            'xl' => 4,
        ];
    }

    // =========================================================
    // TODO: Uncomment kalo data udah live/realtime
    // =========================================================
    public function filtersForm(Schema $schema): Schema
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user?->hasRole('teknisi')) {
            return $schema->components([]);
        }

        return $schema->components([
            Select::make('preset')
                ->label('Time Range')
                ->options([
                    '7'      => 'Last 7 Days',
                    '30'     => 'Last 30 Days',
                    '90'     => 'Last 3 Months',
                    '180'    => 'Last 6 Months',
                    '365'    => 'Last 1 Year',
                    'custom' => 'Custom',
                ])
                ->default('7')
                ->native(false)
                ->live(),
            DatePicker::make('startDate')
                ->label('From')
                ->visible(fn ($get) => $get('preset') === 'custom')
                ->default(now()->subDays(7)),
            DatePicker::make('endDate')
                ->label('To')
                ->visible(fn ($get) => $get('preset') === 'custom')
                ->default(now()),
        ]);
    }
}