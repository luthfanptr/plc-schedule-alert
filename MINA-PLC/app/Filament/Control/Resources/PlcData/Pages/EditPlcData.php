<?php

namespace App\Filament\Control\Resources\PlcData\Pages;

use App\Filament\Control\Resources\PlcData\PlcDataResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPlcData extends EditRecord
{
    protected static string $resource = PlcDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
