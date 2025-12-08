<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UpdateLastSeen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Process the request first
        $response = $next($request);
        
        // Then update the user's last seen status (after response, so it doesn't slow down page loading)
        if (Auth::check()) {
            try {
                // Force update last seen timestamp with direct database update
                \Illuminate\Support\Facades\DB::table('users')
                    ->where('id', Auth::id())
                    ->update([
                        'last_seen_at' => now(),
                        'is_online' => true,
                        'updated_at' => now()
                    ]);
                
                // Also log for debugging
                \Illuminate\Support\Facades\Log::info('Updated last_seen for user: ' . Auth::id() . ' at ' . now());
            } catch (\Exception $e) {
                // Don't break the app if this fails
                \Illuminate\Support\Facades\Log::error('Failed to update last_seen: ' . $e->getMessage());
            }
        }

        return $response;
    }
}
