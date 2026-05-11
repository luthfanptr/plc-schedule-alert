<?php

namespace App\Filament\Admin\Resources\SpkLogs\Pages;

use App\Filament\Admin\Resources\SpkLogs\SpkLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSpkLogs extends ListRecords
{
    protected static string $resource = SpkLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
