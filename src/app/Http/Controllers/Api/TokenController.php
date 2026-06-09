<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TokenResource;
use App\Models\PersonalAccessToken;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TokenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $token = $user->hasRole('super_admin') 
        ? PersonalAccessToken::with('tokenable')
            ->where('description', 'plc_warning')
            ->paginate(15)
        : PersonalAccessToken::with('tokenable')
            ->where('tokenable_id', $user->id)
            ->where('description', 'plc_warning')
            ->paginate(15);

        return TokenResource::collection($token);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'abilities'   => 'required|array',
            'description' => 'nullable|string',
            'expires_at'  => 'nullable|date|after:now',
        ]);

        $newToken = $request->user()->createToken(
            $request->name,
            $request->abilities,
            $request->expires_at ? Carbon::parse($request->expires_at) : null
        );

        $newToken->accessToken->update([
            'description' => 'plc_warning',
            'is_shared'   => true,
        ]);

        $resource = new TokenResource($newToken->accessToken);
        $resource->plain_token = $newToken->plainTextToken;

        return $resource->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $token = $this->findAuthorizedToken($request->user(), $id);
        return new TokenResource($token->load('tokenable'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
         $request->validate([
            'name'       => 'sometimes|string|max:255',
            'abilities'  => 'sometimes|array',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $token = $this->findAuthorizedToken($request->user(), $id);
        $token->update($request->only(['name', 'abilities', 'expires_at']));

        return new TokenResource($token);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $token = $this->findAuthorizedToken($request->user(), $id);
        $token->delete();

        return response()->json(['message' => 'Token successfully revoked']);
    }

    private function findAuthorizedToken($user, $id): PersonalAccessToken
    {
        $token = PersonalAccessToken::findOrFail($id);

        $isOwner = $token->tokenable_id === $user->id;
        $isAdmin = $user->hasRole('super_admin');

        abort_if(!$isOwner && !$isAdmin, 403, 'Don\'t have access to this token');

        return $token;
    }
}
