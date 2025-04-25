<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogUserNameMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user) {
            Log::info("user.name: {$user->name}");
        }

        return $next($request);
    }
}
