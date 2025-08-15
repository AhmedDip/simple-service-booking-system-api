<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Support\CommonResponse;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            $commonResponse = (new CommonResponse())
                ->fail('Access denied: Admins only', null, CommonResponse::STATUS_CODE_FORBID);
            return $commonResponse->commonApiResponse();
        }
        return $next($request);
    }
}
