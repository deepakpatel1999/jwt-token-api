<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
//use Auth;
class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user =  User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ]);
        $role_type = $request->role_type;
        if ($role_type == 'Teacher') {
            $user->attachRole('Teacher');
            $user->attachPermissions(['create-post', 'edit-user']);
        } else {
            $user->attachRole('Student');
            $user->attachPermissions(['edit-user']);
        }
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }


    public function userProfile()
    {
        return response()->json(auth()->user());
    }


    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    public function get_user()
    {
        $data = User::with('roles', 'permissions')->get();
        return response()->json(['data' => $data, 'message' => 'User successfully signed out']);
    }
}
