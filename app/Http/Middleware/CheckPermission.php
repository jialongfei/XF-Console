<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class CheckPermission
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

        if (!in_array($request->path(),['/']) && !Session::get('is_admin') && !in_array('/'.$request->path(),Session::get('permissionlist'))){
            if ($request->ajax()){
                return response()->json(['status'=>false,'msg'=>PER_ERR]);
            }else{
                error_notice(PER_ERR);
            }
        }

        return $next($request);
    }
}
