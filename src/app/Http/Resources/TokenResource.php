<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TokenResource extends JsonResource
{
    public $plain_token = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'abilities'   => $this->abilities,
            'description' => $this->description,
            'is_shared'   => $this->is_shared,
            'last_used_at'=> $this->last_used_at,
            'expires_at'  => $this->expires_at,
            'created_at'  => $this->created_at,
            'created_by'  => $this->whenLoaded('tokenable', fn() => [
                'id'   => $this->tokenable->id,
                'name' => $this->tokenable->name,
            ]),
            // hanya muncul saat create
            'plain_token' => $this->when(
                !is_null($this->plain_token),
                $this->plain_token
            ),
        ];
    }
}
