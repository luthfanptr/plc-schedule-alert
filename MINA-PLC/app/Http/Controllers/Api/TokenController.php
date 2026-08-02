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
            ->paginate(15)
        : PersonalAccessToken::with('tokenable')
            ->where('tokenable_id', $user->id)
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
            'expires_at'  => 'nullable|date|after:now',
        ]);

        $newToken = $request->user()->createToken(
            $request->name,
            $request->abilities,
            $request->expires_at ? Carbon::parse($request->expires_at) : null
        );

        //! enkripsi token di database
        $newToken->accessToken->update([
            'encrypted_plain_token' => $newToken->plainTextToken,
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
        
        $resource = new TokenResource($token->load('tokenable'));
        $resource->plain_token = $token->decrypted_token; //! decrypt token untuk di show

        return $resource;
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
        abort_unless(
            $user->hasRole('super_admin'),
            403,
            'Only Admin can manage API tokens.'
        );

        return PersonalAccessToken::findOrFail($id);
    }
}
