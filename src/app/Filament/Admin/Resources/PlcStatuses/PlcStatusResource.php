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
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PlcStatusResource extends Resource
{
    protected static ?string $model = PlcStatus::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Bell;

    protected static string|null|UnitEnum $navigationGroup = 'Dashboard';

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
