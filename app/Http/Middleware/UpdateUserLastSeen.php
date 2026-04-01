<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UpdateUserLastSeen
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // ✅ Gunakan user_id karena primary key kamu adalah user_id
            $userId = $user->user_id ?? $user->id;

            if (!$userId) {
                Log::error("User tanpa ID detected", [
                    'user' => $user,
                    'route' => $request->route()->getName()
                ]);
                return $next($request);
            }

            $cacheKey = 'user-is-online-' . $userId;

            Log::info("Cache set for user", [
                'user_id' => $userId,
                'user_name' => $user->name,
                'user_role' => $user->role,
                'cache_key' => $cacheKey,
                'route' => $request->route()->getName()
            ]);

            Cache::put($cacheKey, true, now()->addMinutes(1));

            $lastUpdate = $user->last_login_at;
            if (!$lastUpdate || $lastUpdate->diffInMinutes(now()) >= 3) {
                $user->updateQuietly([
                    'last_login_at' => now(),
                ]);
            }
        }

        return $next($request);
    }
}
