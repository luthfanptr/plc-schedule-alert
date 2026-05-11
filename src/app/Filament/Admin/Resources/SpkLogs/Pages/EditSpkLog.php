<?php

namespace App\Filament\Admin\Resources\SpkLogs\Pages;

use App\Filament\Admin\Resources\SpkLogs\SpkLogResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSpkLog extends EditRecord
{
    protected static string $resource = SpkLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
