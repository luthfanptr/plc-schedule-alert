<?php

namespace App\Filament\Control\Resources\PlcData\Pages;

use App\Filament\Control\Resources\PlcData\PlcDataResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPlcData extends ViewRecord
{
    protected static string $resource = PlcDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
