<?php
// app/Http/Middleware/DeviceKey.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DeviceKey
{
    public function handle(Request $request, Closure $next)
    {
        $key = $request->header('x-device-key');
        if (!$key || !in_array($key, config('devices.allowed_keys', []), true)) {
            return response()->json(['error'=>['code'=>'DEVICE_KEY_INVALID']], 401);
        }
        return $next($request);
    }
}