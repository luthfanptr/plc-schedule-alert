<?php

namespace App\Filament\Admin\Resources\SpkLogs;

use App\Filament\Admin\Resources\SpkLogs\Pages\CreateSpkLog;
use App\Filament\Admin\Resources\SpkLogs\Pages\EditSpkLog;
use App\Filament\Admin\Resources\SpkLogs\Pages\ListSpkLogs;
use App\Filament\Admin\Resources\SpkLogs\Schemas\SpkLogForm;
use App\Filament\Admin\Resources\SpkLogs\Tables\SpkLogsTable;
use App\Models\SpkLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SpkLogResource extends Resource
{
    protected static ?string $model = SpkLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return SpkLogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SpkLogsTable::configure($table);
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
            'index' => ListSpkLogs::route('/'),
            'create' => CreateSpkLog::route('/create'),
            'edit' => EditSpkLog::route('/{record}/edit'),
        ];
    }
}
