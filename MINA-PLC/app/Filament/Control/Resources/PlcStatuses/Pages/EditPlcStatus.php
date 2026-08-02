<?php

namespace App\Filament\Control\Resources\PlcStatuses\Pages;

use App\Filament\Control\Resources\PlcStatuses\PlcStatusResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPlcStatus extends EditRecord
{
    protected static string $resource = PlcStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
