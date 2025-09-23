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
            return response()->json(['error'=>['code'=>'UNAUTHORIZED']], 401);
        }
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            'user' => Auth::guard('api')->user(['_id','nombre','rol'])
        ]);
    }

    public function refresh() { return response()->json(['access_token'=>Auth::guard('api')->refresh()]); }
    public function logout()  { Auth::guard('api')->logout(); return response()->json(['ok'=>true]); }
}