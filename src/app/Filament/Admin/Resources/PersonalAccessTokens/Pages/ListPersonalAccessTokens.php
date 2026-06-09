<?php

namespace App\Filament\Admin\Resources\PersonalAccessTokens\Pages;

use App\Filament\Admin\Resources\PersonalAccessTokens\PersonalAccessTokenResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPersonalAccessTokens extends ListRecords
{
    protected static string $resource = PersonalAccessTokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
