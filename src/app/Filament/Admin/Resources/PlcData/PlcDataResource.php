<?php

namespace App\Filament\Admin\Resources\PlcData;

use App\Filament\Admin\Resources\PlcData\Pages\CreatePlcData;
use App\Filament\Admin\Resources\PlcData\Pages\EditPlcData;
use App\Filament\Admin\Resources\PlcData\Pages\ListPlcData;
use App\Filament\Admin\Resources\PlcData\Pages\ViewPlcData;
use App\Filament\Admin\Resources\PlcData\Schemas\PlcDataForm;
use App\Filament\Admin\Resources\PlcData\Schemas\PlcDataInfolist;
use App\Filament\Admin\Resources\PlcData\Tables\PlcDataTable;
use App\Models\PlcData;
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

class PlcDataResource extends Resource
{
    protected static ?string $model = PlcData::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ChartBarSquare;

    protected static string|null|UnitEnum $navigationGroup = 'General';

    public static function form(Schema $schema): Schema
    {
        return PlcDataForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PlcDataInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PlcDataTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //ComponentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPlcData::route('/'),
            'create' => CreatePlcData::route('/create'),
            'view' => ViewPlcData::route('/{record}'),
            'edit' => EditPlcData::route('/{record}/edit'),
        ];
    }

    // filter panel User berdasarkan assignment Plant nya
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
}
