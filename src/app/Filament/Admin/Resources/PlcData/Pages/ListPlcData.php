<?php

namespace App\Filament\Admin\Resources\PlcData\Pages;

use App\Filament\Admin\Resources\PlcData\PlcDataResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPlcData extends ListRecords
{
    protected static string $resource = PlcDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //CreateAction::make(),
        ];
    }
}
