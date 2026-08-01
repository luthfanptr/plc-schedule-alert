<?php

namespace App\Filament\Admin\Resources\PersonalAccessTokens\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Carbon\Carbon;

class PersonalAccessTokensTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Token Name')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('encrypted_plain_token')
                    ->label('Token')
                    ->formatStateUsing(fn($state) => '...' . substr($state, -8))
                    ->fontFamily('mono')
                    ->color('gray'),

                TextColumn::make('abilities')
                    ->label('Abilities')
                    ->badge()
                    ->separator(',')
                    ->color(fn (string $state): string => match (($state)) {
                        'read' => 'info',
                        'update' => 'warning',
                    }),

                TextColumn::make('tokenable.name')
                    ->label('Created By')
                    ->visible(fn() => auth()->check() && auth()->user()->hasRole('super_admin')),

                // TextColumn::make('description')
                //     ->label('Description'),

                TextColumn::make('last_used_at')
                    ->label('Last Used')
                    ->since()
                    ->placeholder('Never')
                    ->sortable(),

                TextColumn::make('expires_at')
                    ->label('Expires')
                    ->formatStateUsing(fn ($state) => $state?->format('M j, Y H:i:s'))
                    ->placeholder('Never')
                    ->color(function ($state) {
                        if (! $state) {
                            return 'gray';
                        }

                        return $state->isPast()
                            ? 'danger'
                            : 'success';
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),
            ])
                ->modifyQueryUsing(function ($query) {
                $user = auth()->user();
                if (!$user->hasRole('super_admin')) {
                    $query->where('tokenable_id', $user->id);
                }
                return $query;
            })
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('detail')
                ->label('Detail')
                ->icon('heroicon-o-eye')
                ->modalHeading('Token Detail')
                ->modalContent(fn($record) => view(
                    'filament.modals.token-detail',
                    ['token' => $record->encrypted_plain_token]
                ))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Close'),

            DeleteAction::make()->label('Revoke'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
