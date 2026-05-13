<?php

namespace App\Filament\Admin\Resources\PlcStatuses\Pages;

use App\Filament\Admin\Resources\PlcStatuses\PlcStatusResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPlcStatus extends EditRecord
{
    protected static string $resource = PlcStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
