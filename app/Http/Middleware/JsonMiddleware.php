<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Closure;

class JsonMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (in_array($request->method(), ['GET','POST', 'PUT', 'DELETE', 'PATCH'])
            && $request->wantsJson()
        ) {
            $data = $request->json()->all();
            $request->request->replace(is_array($data) ? $data : []);
            return $next($request);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401, []);
        }
    }
}
