<?php

namespace App\Filament\Admin\Resources\PlcStatuses\Pages;

use App\Filament\Admin\Resources\PlcStatuses\PlcStatusResource;
use App\Filament\Admin\Resources\PlcStatuses\Widgets\StatusOverview;
//use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Override;

class ListPlcStatuses extends ListRecords
{
    protected static string $resource = PlcStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //CreateAction::make(),
        ];
    }

    #[Override]
    protected function getHeaderWidgets(): array
    {
        return [
            StatusOverview::class,
        ];  
    }
}
