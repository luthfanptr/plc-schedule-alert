<?php

namespace App\Filament\Admin\Resources\PlcNotifications;

use App\Filament\Admin\Resources\PlcNotifications\Pages\CreatePlcNotification;
use App\Filament\Admin\Resources\PlcNotifications\Pages\EditPlcNotification;
use App\Filament\Admin\Resources\PlcNotifications\Pages\ListPlcNotifications;
use App\Filament\Admin\Resources\PlcNotifications\Schemas\PlcNotificationForm;
use App\Filament\Admin\Resources\PlcNotifications\Tables\PlcNotificationsTable;
use App\Models\PlcNotification;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PlcNotificationResource extends Resource
{
    protected static ?string $model = PlcNotification::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PlcNotificationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PlcNotificationsTable::configure($table);
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
            'index' => ListPlcNotifications::route('/'),
            'create' => CreatePlcNotification::route('/create'),
            'edit' => EditPlcNotification::route('/{record}/edit'),
        ];
    }
}
