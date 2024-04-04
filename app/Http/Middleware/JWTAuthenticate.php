<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JWTAuthenticate
{
    public function handle(Request $request, Closure $next): Response
    {

        $rawToken = $request->cookie('access_token');
        $request->headers->set('Authorization', 'Bearer '.$rawToken);
        return $next($request) ;
    }
}