<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('post')) {
            return $next($request);
        }

        $acceptHeader = explode(',', $request->header('Accept'));
        if (! in_array('application/json', $acceptHeader)) {
            return response()->json([
                'message' => 'Not Acceptable',
                'status' => 406,
            ], 406);
        }

        return $next($request);
    }
}
