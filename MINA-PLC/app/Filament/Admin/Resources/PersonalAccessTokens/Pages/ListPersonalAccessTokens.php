<?php

namespace App\Filament\Admin\Resources\PersonalAccessTokens\Pages;

use App\Filament\Admin\Resources\PersonalAccessTokens\PersonalAccessTokenResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Width;

class ListPersonalAccessTokens extends ListRecords
{
    protected static string $resource = PersonalAccessTokenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New API Key')
                ->modalHeading('Create API Key')
                ->modalDescription('Generate a token to authenticate API requests.')
                ->modalWidth(Width::ScreenLarge)
                ->modalAlignment(Alignment::Start)
                ->modalSubmitActionLabel('Create')
                ->modalCancelActionLabel('Cancel')
                ->createAnother(false)
                
                ->using(function (array $data) {
                    $user = auth()->user();

                    $token = $user->createToken(
                        name: $data['name'],
                        abilities: $data['abilities'] ?? ['*'],
                    );

                    $token->accessToken->update([
                        'encrypted_plain_token' => $token->plainTextToken,
                        'expires_at' => $data['expires_at'] ?? null,
                    ]);

                    return $token->accessToken;
                }),
        ];
    }
}
