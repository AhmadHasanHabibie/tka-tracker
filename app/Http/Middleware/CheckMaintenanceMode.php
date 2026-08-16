<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $maintenanceFile = storage_path('app/maintenance.json');

        if (file_exists($maintenanceFile)) {
            $data = json_decode(file_get_contents($maintenanceFile), true);

            if (!empty($data['is_active'])) {
                $user = auth()->user();

                // Allow Admin users who have verified PIN
                if ($user && $user->isAdmin() && session('admin_pin_verified') === true) {
                    return $next($request);
                }

                // Allow PIN verification routes for admin login flow
                if ($request->is('admin/pin*') || $request->routeIs('admin.pin.*')) {
                    return $next($request);
                }

                // Allow login and logout routes (by path or route name) even during maintenance
                if ($request->is('login') || $request->is('logout') || $request->routeIs('login') || $request->routeIs('logout')) {
                    return $next($request);
                }

                // Render maintenance view with 503 status
                return response()->view('maintenance', [
                    'message' => $data['message'] ?? 'Website sedang dalam pemeliharaan/maintenance.',
                ], 503);
            }
        }

        return $next($request);
    }
}
