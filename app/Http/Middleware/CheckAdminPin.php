<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminPin
{
    /**
     * Handle an incoming request for Admin routes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || !$user->isAdmin()) {
            abort(403, 'Akses ditolak. Halaman khusus Administrator.');
        }

        // Exclude PIN form and PIN post verification from pin check loop
        if ($request->routeIs('admin.pin.show') || $request->routeIs('admin.pin.verify')) {
            return $next($request);
        }

        if (session('admin_pin_verified') !== true) {
            return redirect()->route('admin.pin.show');
        }

        return $next($request);
    }
}
