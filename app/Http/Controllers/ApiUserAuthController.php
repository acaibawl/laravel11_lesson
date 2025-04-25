<?php

namespace App\Http\Controllers;

//use App\Facades\EatLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiUserAuthController extends Controller // implements HasMiddleware
{
//    public static function middleware(): array
//    {
//        return [
//            'auth',
//            new Middleware(middleware: 'auth:api_user', except: ['login', 'register']),
//        ];
//    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('name', 'password');
        // name・password（自動でハッシュする）で検索をかけて、一致するuserがいればtokenを設定。なければfalseが入る
        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        \EatLog::eat('りんご');
        return $this->respondWithToken($token);
    }

    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Registration successful',
            'token' => $token,
        ]);
    }

    public function me(): JsonResponse
    {
        $user = auth()->user();
        return response()->json($user);
    }

    public function logout(): JsonResponse
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
//            'expires_in' => auth()->factory()->getTTL() * 60,
            'expires_in' => config('jwt.ttl'),
        ]);
    }
}
