<?php

namespace App\Filament\Admin\Resources\PersonalAccessTokens\Pages;

use App\Filament\Admin\Resources\PersonalAccessTokens\PersonalAccessTokenResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPersonalAccessToken extends EditRecord
{
    protected static string $resource = PersonalAccessTokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
