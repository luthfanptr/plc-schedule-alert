<?php

namespace App\Filament\Admin\Resources\PlcNotifications\Pages;

use App\Filament\Admin\Resources\PlcNotifications\PlcNotificationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPlcNotification extends EditRecord
{
    protected static string $resource = PlcNotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
