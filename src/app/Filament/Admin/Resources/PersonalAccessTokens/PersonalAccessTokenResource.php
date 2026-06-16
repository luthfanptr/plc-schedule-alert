<?php

namespace App\Filament\Admin\Resources\PersonalAccessTokens;

use App\Filament\Admin\Resources\PersonalAccessTokens\Pages\CreatePersonalAccessToken;
use App\Filament\Admin\Resources\PersonalAccessTokens\Pages\ListPersonalAccessTokens;
use App\Filament\Admin\Resources\PersonalAccessTokens\Schemas\PersonalAccessTokenForm;
use App\Filament\Admin\Resources\PersonalAccessTokens\Tables\PersonalAccessTokensTable;
use App\Models\PersonalAccessToken;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class PersonalAccessTokenResource extends Resource
{
    protected static ?string $model = PersonalAccessToken::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-s-key';

    protected static string|null|UnitEnum $navigationGroup = 'Access';

    public static function form(Schema $schema): Schema
    {
        return PersonalAccessTokenForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PersonalAccessTokensTable::configure($table);
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
            'index' => ListPersonalAccessTokens::route('/'),
            'create' => CreatePersonalAccessToken::route('/create'),
            //'edit' => EditPersonalAccessToken::route('/{record}/edit'),
        ];
    }

    // modul PAT hanya tampil utk role ini
    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('super_admin');
    }
}
