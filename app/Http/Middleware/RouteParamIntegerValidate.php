<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class RouteParamIntegerValidate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $params = ['post_id', 'category_id', 'tag_id'];
        foreach ($params as $param) {
            if ($route = $request->route($param)) {
                if (!is_numeric($route) || !is_integer((int)$route)) {
                    return response()->json([
                        'message' => 'The given data was invalid.',
                        'errors' => [$param => 'Parameter must be integer.'],
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            }
        }
        return $next($request);
    }
}
