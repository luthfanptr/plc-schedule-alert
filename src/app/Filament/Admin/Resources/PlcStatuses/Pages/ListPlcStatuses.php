<?php

namespace App\Filament\Admin\Resources\PlcStatuses\Pages;

use App\Filament\Admin\Resources\PlcStatuses\PlcStatusResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPlcStatuses extends ListRecords
{
    protected static string $resource = PlcStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //CreateAction::make(),
        ];
    }
}
