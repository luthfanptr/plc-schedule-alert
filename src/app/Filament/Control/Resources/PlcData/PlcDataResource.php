<?php

namespace App\Filament\Control\Resources\PlcData;

use App\Filament\Control\Resources\PlcData\Pages\CreatePlcData;
use App\Filament\Control\Resources\PlcData\Pages\EditPlcData;
use App\Filament\Control\Resources\PlcData\Pages\ListPlcData;
use App\Filament\Control\Resources\PlcData\Pages\ViewPlcData;
use App\Filament\Control\Resources\PlcData\Schemas\PlcDataForm;
use App\Filament\Control\Resources\PlcData\Schemas\PlcDataInfolist;
use App\Filament\Control\Resources\PlcData\Tables\PlcDataTable;
use App\Models\PlcData;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PlcDataResource extends Resource
{
    protected static ?string $model = PlcData::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ChartBarSquare;

    protected static string|null|UnitEnum $navigationGroup = 'Dashboard';

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
            //
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
}
