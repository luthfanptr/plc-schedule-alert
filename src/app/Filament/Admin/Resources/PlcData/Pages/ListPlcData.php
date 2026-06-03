<?php

namespace App\Filament\Admin\Resources\PlcData\Pages;

use App\Filament\Admin\Resources\PlcData\PlcDataResource;
use App\Filament\Admin\Resources\PlcData\Widgets\DataStatsOverview;
use App\Jobs\StatusJob;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Override;

class ListPlcData extends ListRecords
{
    use ExposesTableToWidgets;

    public bool $isSyncing = false;

    protected static string $resource = PlcDataResource::class;

    protected $listeners = [
        'stopPolling' => 'disablePolling',
    ];

    public function disablePolling()
    {
        $this->isSyncing = false;
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->icon('heroicon-m-list-bullet'),

            'standard' => Tab::make('Standard')
                ->icon('heroicon-m-shield-check')
                // Ambil semua grup yang memiliki minimal satu status STANDARD
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereIn('plc_id', function ($subQuery) {
                        $subQuery->select('plc_id')
                            ->from('plc_data')
                            ->where('status', 'STANDARD');
                    })
                ),

            'warning' => Tab::make('Warning')
                ->badgeColor('warning')
                ->icon('heroicon-m-exclamation-circle')
                // Ambil semua grup yang memiliki minimal satu status WARNING
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereIn('plc_id', function ($subQuery) {
                        $subQuery->select('plc_id')
                            ->from('plc_data')
                            ->where('status', 'WARNING');
                    })
                ),

            'danger' => Tab::make('Danger')
                ->badgeColor('danger')
                ->icon('heroicon-m-exclamation-triangle')
                // Ambil semua grup yang memiliki minimal satu status DANGER
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereIn('plc_id', function ($subQuery) {
                        $subQuery->select('plc_id')
                            ->from('plc_data')
                            ->where('status', 'DANGER');
                    })
                ),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('syncPlcData')
                ->label('Run New Job')
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->requiresConfirmation()
                ->modalIcon('heroicon-o-arrow-path')
                ->action(function () {
                    // Cukup kirim ke queue, prosesnya akan dikerjakan di background
                    StatusJob::dispatch();

                    // ubah properti lokal halaman
                    $this->isSyncing = true;
                    // kirim sinyal global supaya halaman plcstatuses tau kalau job sedang jalan
                    $this->dispatch('globalJob');
                }),
            // Actions\CreateAction::make(),
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
