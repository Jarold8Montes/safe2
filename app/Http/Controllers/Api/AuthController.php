<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $r)
    {
        $credentials = $r->only('email','password');
        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return $this->sendError('UNAUTHORIZED', [], 401);
        }
        return $this->sendResponse([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            'user' => Auth::guard('api')->user(['_id','nombre','rol'])
        ], 'Login exitoso', 200);
    }

    public function refresh()
    {
        return $this->sendResponse([
            'access_token' => Auth::guard('api')->refresh()
        ], 'Token refrescado', 200);
    }
    public function logout()
    {
        Auth::guard('api')->logout();
        return $this->sendResponse([], 'Sesión cerrada', 200);
    }
}