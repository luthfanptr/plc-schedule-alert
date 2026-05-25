<?php

namespace App\Filament\Admin\Resources\PlcStatuses;

use App\Filament\Admin\Resources\PlcStatuses\Pages\CreatePlcStatus;
use App\Filament\Admin\Resources\PlcStatuses\Pages\EditPlcStatus;
use App\Filament\Admin\Resources\PlcStatuses\Pages\ListPlcStatuses;
use App\Filament\Admin\Resources\PlcStatuses\Pages\ViewPlcStatus;
use App\Filament\Admin\Resources\PlcStatuses\Schemas\PlcStatusForm;
use App\Filament\Admin\Resources\PlcStatuses\Schemas\PlcStatusInfolist;
use App\Filament\Admin\Resources\PlcStatuses\Tables\PlcStatusesTable;
use App\Models\PlcStatus;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Override;
use UnitEnum;

class PlcStatusResource extends Resource
{
    protected static ?string $model = PlcStatus::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Bell;

    protected static string|null|UnitEnum $navigationGroup = 'Dashboard';

    // filter data di filament sesuai plant assigned
    #[Override]
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (Filament::getCurrentPanel()->getId() === 'control') {
            /** @var App\Models\User $user */
            $user = Auth::user();

            $assignedPlant = $user->plants()->pluck('Name')->toArray();

            return $query->whereIn('plant', $assignedPlant);
        }
        return $query;
    }

    // notif dinamis sesuai total component warning & danger pada tab dashboard
    public static function getNavigationBadge(): ?string
    {
        $baseQuery = PlcStatus::query();
        if (Filament::getCurrentPanel()->getId() === 'control') {
            /** @var App\Models\User $user */
            $user = Auth::user();

            if ($user && ! $user->hasRole('super_admin')) {
                $assignedPlant = $user->plants->pluck('Name')->toArray();
                $baseQuery->whereIn('plant', $assignedPlant);
            }
            $count = $baseQuery->whereIn('status', ['WARNING', 'DANGER'])
                ->where(function ($query) {
                    $query->where('spk_status', '!=', 'done')
                        ->orWhereNull('spk_status');
                })
                ->count();

            if ($count > 0) {
                return (string) $count;
            }
        }
        return null;
    }

    public static function form(Schema $schema): Schema
    {
        return PlcStatusForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PlcStatusInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PlcStatusesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPlcStatuses::route('/'),
            'create' => CreatePlcStatus::route('/create'),
            'view' => ViewPlcStatus::route('/{record}'),
            'edit' => EditPlcStatus::route('/{record}/edit'),
        ];
    }
}
