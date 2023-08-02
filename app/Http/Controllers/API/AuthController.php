<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Requests\UtilisatorRequest;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['login', 'register']]);
    }
    public function login(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
            'role' => 'required|string|in:admin,teacher,student',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Retrieve authentication data
        $credentials = $request->only('email', 'password');

        // Check if user is authenticated
        $user = User::where('email', $request->input('email'))->first();

        if (!in_array($request->input('role'), ['admin', 'teacher', 'student'])) {
            return response()->json([
                'message' => 'Invalid role!',
            ], 400);
        }

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'message' => 'Invalid authentication data',
            ], 401);
        }

        // Generate a new access token using Laravel Passport
        $accessToken = $user->createToken('ApiToken')->accessToken;

        // Get the user's ID
        $userId = $user->id;

        // Create the response array
        $response = [
            'user' => $user,
            'authorization' => [
                'token' => $accessToken,
                'type' => 'bearer',
                'userId' => $userId,
            ]
        ];

        return response()->json($response);
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|max:15|unique:users',
            'role' => 'required|in:admin,teacher,student',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $role = $request->input('role');
        $firstname = $request->input('firstname');
        $lastname = $request->input('lastname');
        $phone = $request->input('phone');
        $email = $request->input('email');
        $password = $request->input('password');

        $userData = $request->except('role');

        $utilisatorRequest = new UtilisatorRequest($userData);

        // Process the registration based on the user's role
        switch ($role) {
            case 'admin':
                return app(AdministratorController::class)->store($utilisatorRequest);
            case 'teacher':
                return app(TeacherController::class)->store($utilisatorRequest);
            case 'student':
                return app(StudentController::class)->store($utilisatorRequest);
            default:
                return response()->json(['message' => 'Invalid role!'], 400);
        }
    }

    public function logout()
    {
        // Revoke the user's access tokens
        $user = Auth::user();
        $user->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        // Revoke the user's existing tokens and create a new one
        $user = Auth::user();
        $user->tokens->each(function ($token, $key) {
            $token->delete();
        });

        $token = $user->createToken('ApiToken')->accessToken;

        return response()->json([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }
}
