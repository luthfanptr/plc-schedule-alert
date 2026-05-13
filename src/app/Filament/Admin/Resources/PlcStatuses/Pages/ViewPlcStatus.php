<?php

namespace App\Filament\Admin\Resources\PlcStatuses\Pages;

use App\Filament\Admin\Resources\PlcStatuses\PlcStatusResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPlcStatus extends ViewRecord
{
    protected static string $resource = PlcStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
