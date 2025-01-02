<?php

namespace App\Http\Middleware;

use App\Http\Responses\JsonResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class HasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if ($role !== Auth::user()->role) {
            return JsonResponse::forbidden('You do not have access to perform this action.');
        }
        return $next($request);
    }
}
