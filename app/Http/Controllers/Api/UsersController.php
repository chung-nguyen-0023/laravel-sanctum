<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\TokenResource;

class UsersController extends Controller
{
    public function index()
    {
        if (!auth()->user()->tokenCan('users-list')) {
            abort(403, 'Unauthorized');
        }
        $users = User::all();
        return UserResource::collection($users);
    }

    public function show($id)
    {
        if (!auth()->user()->tokenCan('users-show')) {
            abort(403, 'Unauthorized');
        }
        $user = User::find($id);
        return new UserResource($user);
    }

    public function getAllTokens()
    {
        if (!auth()->user()->tokenCan('token-list')) {
            abort(403, 'Unauthorized');
        }
        $tokens = auth()->user()->tokens;
        return TokenResource::collection($tokens);
    }

    public function deleteAllTokens()
    {
        if (!auth()->user()->tokenCan('token-remove')) {
            abort(403, 'Unauthorized');
        }
        auth()->user()->tokens()->delete();
        return response()->json([
            'status_code' => 200,
            'success' => true,
        ]);
    }

    public function deleteToken($tokenId)
    {
        if (!auth()->user()->tokenCan('token-remove')) {
            abort(403, 'Unauthorized');
        }
        auth()->user()->tokens()->where('id', $tokenId)->delete();
        return response()->json([
            'status_code' => 200,
            'success' => true,
        ]);
    }
}
