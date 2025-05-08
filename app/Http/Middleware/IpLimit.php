<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IpLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->runningUnitTests()) {
            return $next($request);
        }

        // 10.0.0.1以外は403にする
        if (!in_array($request->ip(), ['10.0.0.1'], true)) {
            abort(403, 'Your IP is not valid.');
        }

        return $next($request);
    }

    protected function runningUnitTests(): bool
    {
        return app()->runningInConsole() && app()->runningInConsole();
    }
}
