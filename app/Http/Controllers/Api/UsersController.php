<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function index()
    {
        if (!auth()->user()->tokenCan('users-view')) {
            abort(403, 'Unauthorized');
        }
        $users = User::all();
        return response()->json([
            'status_code' => 200,
            'data' => $users,
        ]);
    }

    public function show($id)
    {
        if (!auth()->user()->tokenCan('users-view')) {
            abort(403, 'Unauthorized');
        }
        $user = User::find($id);
        return response()->json([
            'status_code' => 200,
            'data' => $user,
        ]);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->tokenCan('users-create')) {
            abort(403, 'Unauthorized');
        }

        $validator = Validator::make($request->all(), [
            'role' => ['required', 'regex:/[admin|user-management|category-management]/'],
            'email' => 'required',
            'password' => 'required',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Validate fail',
                'error' => $validator->errors(),
            ]);
        }

        if (User::where('email', $request->get('email'))->first()) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Email already exist',
            ]);
        }
        $data                 = $request->all();
        $user                 = User::create($data);
        $user->password       = Hash::make($data['password']);
        $user->save();

        $token = '';
        switch ($request->get('role')) {
            case 'admin':
                $token = $user->createToken('authToken')->plainTextToken;
                break;
            case 'user-management':
                $user_permissions = [
                    'users-view',
                    'users-create',
                    'users-update',
                    'users-delete',
                ];
                $token = $user->createToken('authToken', $user_permissions)->plainTextToken;
                break;
            case 'category-management':
                $category_permissions = [
                    'categories-view',
                    'categories-create',
                    'categories-update',
                    'categories-delete',
                ];
                $token = $user->createToken('authToken', $category_permissions)->plainTextToken;
                break;
        }

        return response()->json([
            'status_code' => 200,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function update($id, Request $request)
    {
        if (!auth()->user()->tokenCan('users-update')) {
            abort(403, 'Unauthorized');
        }

        $user = User::findOrFail($id);
        $user->update($request->all());

        return response()->json([
            'status_code' => 200,
            'data' => $user,
        ]);
    }

    public function destroy($id)
    {
        if (!auth()->user()->tokenCan('users-delete')) {
            abort(403, 'Unauthorized');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'status_code' => 200,
            'success' => true,
        ]);
    }

    public function getAllTokens()
    {
        if (!auth()->user()->tokenCan('users-update')) {
            abort(403, 'Unauthorized');
        }
        $tokens = auth()->user()->tokens;
        return response()->json([
            'status_code' => 200,
            'data' => $tokens,
        ]);
    }

    public function deleteAllTokens()
    {
        if (!auth()->user()->tokenCan('users-update')) {
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
        if (!auth()->user()->tokenCan('users-update')) {
            abort(403, 'Unauthorized');
        }
        auth()->user()->tokens()->where('id', $tokenId)->delete();
        return response()->json([
            'status_code' => 200,
            'success' => true,
        ]);
    }
}
