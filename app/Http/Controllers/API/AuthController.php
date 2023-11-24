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
use App\Models\Administrator;
use App\Models\Teacher;
use App\Models\Student;
use Laravel\Sanctum\PersonalAccessToken;
class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
{
    // Validation des données de la demande
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

    // Récupérer les données d'authentification
    $email = $request->input('email');
    $password = $request->input('password');
    $role = $request->input('role');

    // Vérifier si l'utilisateur est authentifié en fonction du rôle
    $user = null;
    switch ($role) {
        case 'admin':
            $user = Administrator::where('email', $email)->first();
            break;
        case 'teacher':
            $user = Teacher::where('email', $email)->first();
            break;
        case 'student':
            $user = Student::where('email', $email)->first();
            break;
        default:
            return response()->json([
                'message' => 'Invalid role!',
            ], 400);
    }

    // Vérifier si l'utilisateur existe et si le mot de passe correspond
    if (!$user || !Hash::check($password, $user->password)) {
        return response()->json([
            'message' => 'Invalid authentication data!',
        ], 401);
    }

    // Générer un nouveau jeton d'accès à l'aide de Sanctum
    $token = $user->createToken('ApiToken');

    // Obtenir l'ID de l'utilisateur
    $userId = $user->id;

    // Créer le tableau de réponse
    $response = [
        'user' => $user,
        'authorization' => [
            'token' => $token->plainTextToken,
            'type' => 'bearer',
            'userId' => $userId,
        ],
        'role' =>$role
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
        // Révoquer le jeton actuel
        $user = Auth::user();
        $user->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh(Request $request)
{
    // Récupérez l'utilisateur authentifié
    $user = Auth::user();

    // Révoquez tous les jetons de l'utilisateur
    $user->tokens->each(function ($token, $key) {
        $token->delete();
    });

    // Créez un nouveau jeton Sanctum
    $token = $user->createToken('ApiToken')->plainTextToken;

    return response()->json([
        'user' => $user,
        'authorization' => [
            'token' => $token,
            'type' => 'bearer',
        ]
    ]);
}

}
