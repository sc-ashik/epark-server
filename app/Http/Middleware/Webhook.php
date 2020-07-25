<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Spatie\Permission\Models\Role;

class Webhook
{ 
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$guard = null)
    {
        if (Auth::guard('api')->check()) {
        // Here you have access to $request->user() method that
        // contains the model of the currently authenticated user.
        //
        // Note that this method should only work if you call it
        // after an Auth::check(), because the user is set in the
        // request object by the auth component after a successful
        // authentication check/retrival
            $user=Auth::guard('api')->user();
            if($user->hasAnyRole(["viewer","admin"]))
                return response()->json(['x-hasura-role' => 'user', 'x-hasura-user-id' => '1']);
            // else
                // return response("Unauthorized Role",401);
 
        }

        // return general data
        return response("Unauthorized",401);

      
        return $next($request);
    }
}
