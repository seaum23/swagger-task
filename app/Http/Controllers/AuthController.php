<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }
    
    public function loginView(){
        return response()->json([
            'message' => 'Unauthorized',
        ], 401);
    }

    /**
     * @OA\Post(
     *   path="/login",
     *   summary="Sign in",
     *   description="Login by email, password",
     *   operationId="authLogin",
     *   tags={"auth"},
     *   @OA\RequestBody(
     *      required=true,
     *      description="Pass user credentials",
     *      @OA\JsonContent(
     *         required={"email","password"},
     *         @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *         @OA\Property(property="password", type="string", format="password", example="PassWord12345")
     *      ),
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Wrong credentials response",
     *      @OA\JsonContent(
     *         @OA\Property(property="message", type="string", example="Unauthorized")
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\JsonContent(
     *         @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *         @OA\Property(property="authorisation", type="string", format="array", example={"token":"aaaaaaa.aaaaaaa.aaaaaaa","type":"bearer"}),
     *      )
     *   )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $token = Auth::attempt($request->only('email', 'password'));
        
        if (!$token) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();

        return response()->json([
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    /**
     * @OA\Post(
     *   path="/register",
     *   summary="Register user",
     *   description="Register user",
     *   operationId="registerUser",
     *   tags={"auth"},
     *   @OA\RequestBody(
     *      required=true,
     *      description="Pass user data",
     *      @OA\JsonContent(
     *         required={"name", "email","password"},
     *         @OA\Property(property="name", type="string", maxLength=32, example="Jhon Doe"),
     *         @OA\Property(property="email", type="string", format="email", maxLength=255, example="user1@mail.com"),
     *         @OA\Property(property="password", type="string", format="password", minLength=6, example="PassWord12345")
     *      ),
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Successfully registered user.",
     *      @OA\JsonContent(
     *         @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *         @OA\Property(property="authorisation", type="string", format="array", example={"token":"aaaaaaa.aaaaaaa.aaaaaaa","type":"bearer"}),
     *      )
     *   ),
     *   @OA\Response(
     *      response=422,
     *      description="Duplicate email.",
     *      @OA\JsonContent(
     *         @OA\Property(property="message", type="string", example="The email has already been taken."),
     *         @OA\Property(property="authorisation", type="string", format="array", example={"errors": { "email" : { "The email has already been taken." } }}),
     *      )
     *   )
     * )
     */
    public function register(Request $request){
        
        $request->validate([
            'name' => 'required|string|max:32',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);

        return response()->json([
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    /**
     * @OA\Post(
     *   path="/logout",
     *   summary="Logout",
     *   description="Logout user and invalidate token",
     *   operationId="authLogout",
     *   tags={"auth"},
     *   security={ {"bearer": {} }},
     *   @OA\Response(
     *      response=200,
     *      description="Success"
     *   )
     * )
     */
    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
