<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

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
}
