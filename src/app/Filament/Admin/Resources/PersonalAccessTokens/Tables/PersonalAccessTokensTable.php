<?php

namespace App\Filament\Admin\Resources\PersonalAccessTokens\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PersonalAccessTokensTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Token Name')
                    ->searchable(),

                TextColumn::make('plain_token')
                    ->label('Token')
                    ->formatStateUsing(fn($state) => '...' . substr($state, -8)),

                TextColumn::make('abilities')
                    ->label('Abilities')
                    ->badge(),

                TextColumn::make('tokenable.name')
                    ->label('Created By')
                    ->visible(fn() => auth()->check() && auth()->user()->hasRole('super_admin')),

                TextColumn::make('description')
                    ->label('Description'),

                TextColumn::make('last_used_at')
                    ->label('Last Used')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('expires_at')
                    ->label('Expires At')
                    ->dateTime()
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
                return $query->where('description', 'plc_warning');
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
                    ['token' => $record->plain_token]
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
