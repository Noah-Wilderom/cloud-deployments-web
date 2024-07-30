<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InternalApiOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $referer = $request->headers->get('referer');
        $origin = $request->headers->get('origin');

        $appUrl = config('app.url');

        if (!$this->isInternalRequest($referer, $origin, $appUrl)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }

    protected function isInternalRequest($referer, $origin, $appUrl)
    {
        return ($referer && str_starts_with($referer, $appUrl)) ||
            ($origin && str_starts_with($origin, $appUrl));
    }
}
