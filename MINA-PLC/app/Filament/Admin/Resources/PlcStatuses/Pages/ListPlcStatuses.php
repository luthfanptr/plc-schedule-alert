<?php

namespace App\Filament\Admin\Resources\PlcStatuses\Pages;

use App\Filament\Admin\Resources\PlcStatuses\PlcStatusResource;
use App\Filament\Admin\Resources\PlcStatuses\Widgets\StatusOverview;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Override;

class ListPlcStatuses extends ListRecords
{
    // properti pemantau status sync halaman plcstatuses
    public bool $isSyncing = false;
    protected static string $resource = PlcStatusResource::class;

    // listener untuk menangkap sinyal globalJob
    protected $listeners = [
        'globalJob' => 'enablePolling',
        'stopPolling' => 'disablePolling',
    ];

    public function enablePolling()
    {
        $this->isSyncing = true;
    }

    public function disablePolling()
    {
        $this->isSyncing = false;
    }

    #[Override]
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->icon('heroicon-m-list-bullet'),
            
            'warning' => Tab::make('Warning')
                ->badgeColor('warning')
                ->icon('heroicon-m-exclamation-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereIn('plc_id', function ($subQuery) {
                        $subQuery->select('plc_id')
                            ->from('plc_statuses')
                            ->where('status', 'WARNING');
                    })
                ),
            
            'danger' => Tab::make('Danger')
                ->badgeColor('danger')
                ->icon('heroicon-m-exclamation-triangle')
                ->modifyQueryUsing(fn (Builder $query) => $query
                    ->whereIn('plc_id', function ($subQuery) {
                        $subQuery->select('plc_id')
                            ->from('plc_statuses')
                            ->where('status', 'DANGER');
                    })
                ),
        ];  
    }

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
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
