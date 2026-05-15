<?php

namespace App\Filament\Admin\Resources\PlcData\Pages;

use App\Filament\Admin\Resources\PlcData\PlcDataResource;
use App\Filament\Admin\Resources\PlcData\PlcDataResource\Widgets\DataStatsOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Jobs\StatusJob;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Illuminate\Contracts\View\View;
use Override;

class ListPlcData extends ListRecords
{
    use ExposesTableToWidgets;
    
    protected static string $resource = PlcDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('syncPlcData')
                ->label('Run New Job')
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->action(function () {
                    // Cukup kirim ke queue, prosesnya akan dikerjakan di background
                    StatusJob::dispatch();
                }),
           //Actions\CreateAction::make(),
        ];
    }

    #[Override]
    protected function getHeaderWidgets(): array
    {
        return [
            DataStatsOverview::class,
        ];
    }
}
