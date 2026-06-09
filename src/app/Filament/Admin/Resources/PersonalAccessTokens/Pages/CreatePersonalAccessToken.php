<?php

namespace App\Filament\Admin\Resources\PersonalAccessTokens\Pages;

use App\Filament\Admin\Resources\PersonalAccessTokens\PersonalAccessTokenResource;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePersonalAccessToken extends CreateRecord
{
    protected static string $resource = PersonalAccessTokenResource::class;

    public ?string $plainToken = null;

    protected function handleRecordCreation(array $data): Model
    {
        /**App\Models\User $user */
        $user = auth()->user();

        $newToken = $user->createToken(
            $data['name'],
            $data['abilities'],
            !empty($data['expires_at']) ? Carbon::parse($data['expires_at']) : null
        );

        $newToken->accessToken->forceFill([
            'description' => 'plc_warning',
            'is_shared'   => true,
            'plain_token' => $newToken->plainTextToken,
        ])->save();

        return $newToken->accessToken;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['has_expiry'])) {
            $data['expires_at'] = null;
        }

        unset($data['has_expiry']);
        return $data;
    }
}
