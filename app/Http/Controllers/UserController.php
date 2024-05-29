<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    /**
     * Display list of all users
     */
    public function index()
    {
        $users = User::all();
        return $users;
    }

    public function active()
    {
        $users = User::where('status',User::ACTIVE)->get();
        return $users;
    }

    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|min:8',
        ];

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json(['success' => false, 'message' => 'User data validation error', 'error' => $validation->messages()], 422);
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => USER::ACTIVE,
                'role_id' => 2,
            ]);

            $role = Role::where('name', 'user')->first();
            $user->role()->associate($role);
            $token = Auth::login($user);
            // $roles = $user->role();
            // dd($roles);
            return response()->json(['success' => true, 'message' => 'User created successfully', 'data' => ['user_id' => $user->id]], 200);
        }
    }

    /**
     * Allows a user to login
     */
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required',
            'password' => 'required|min:8',
        ];
        $data = $request->only('email', 'password');
        $validation = Validator::make($data, $rules);
        if ($validation->fails()) {
            return response()->json(['success' => false, 'message' => 'User data validation error', 'error' => $validation->messages()], 422);
        } else {
            $token = Auth::attempt($data);

            if (!$token) {
                return response()->json(['success' => false, 'message' => 'User not authorized'], 401);
            } else {
                $user = Auth::user();
                return response()->json(['success' => true, 'message' => 'Logged in successfully', 'data' => ['user' => $user, 'token' => $token]], 200);
            }
        }
    }
    /**
     * Allows a user to changes its activity status
     */
    public function changeStatus($user_id)
    {
        $user = User::where('id', $user_id)->first();
        if (empty($user)) {
            return response()->json(['success' => false, 'message' => 'User does not exist'], 200);
        } else {
            if ($user->status == User::ACTIVE) {
                $user->status = User::INACTIVE;
            } else {
                $user->status = User::ACTIVE;
            }
            return response()->json(['success' => true, 'message' => 'Updated status successfully'], 200);
        }
    }
}
