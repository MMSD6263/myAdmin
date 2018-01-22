<?php

namespace App\Http\Middleware;

use Closure;

class AdminLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $action = \Route::current()->getActionName();
        $nodes = session('admin.powers');

        $path = array_reverse(explode('\\', $action))[0];
        $path = strtolower(explode('@', $path)[0]);
        $path = str_replace('controller', '', $path);
        $path = empty($path) ? '######' : $path;
        $nodes = strtolower(str_replace('\/', '', $nodes));
        $nodes = empty($nodes) ? '@@@@@' : strtolower($nodes);


        if (empty($request)) {
            return redirect('login/index');
        } else if (($path != 'index') && $nodes != '@@@@@' && ($path != 'login')) {
            if (!(stripos($nodes, $path))) {
                return redirect('login/index');
            }
        }

        return $next($request);
    }
}