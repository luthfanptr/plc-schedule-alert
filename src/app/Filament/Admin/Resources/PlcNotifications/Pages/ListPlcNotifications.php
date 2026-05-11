<?php

namespace App\Filament\Admin\Resources\PlcNotifications\Pages;

use App\Filament\Admin\Resources\PlcNotifications\PlcNotificationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPlcNotifications extends ListRecords
{
    protected static string $resource = PlcNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
