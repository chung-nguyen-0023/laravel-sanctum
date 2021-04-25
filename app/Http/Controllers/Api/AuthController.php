<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            $credentials = request(['email', 'password']);

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Unauthorized'
                ]);
            }

            $user = User::where('email', $request->email)->first();

            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Error in Login');
            }

            // Create token with full permission
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            // Create token with specify permission
            // $tokenResult = $user->createToken('authToken', ['categories-list'])->plainTextToken;

            return response()->json([
                'status_code' => 200,
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in Login',
                'error' => $error,
            ]);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required',
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
}
