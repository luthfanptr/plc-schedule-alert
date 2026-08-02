<?php

namespace App\Filament\Control\Resources\PlcData\Pages;

use App\Filament\Control\Resources\PlcData\PlcDataResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPlcData extends ListRecords
{
    protected static string $resource = PlcDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
