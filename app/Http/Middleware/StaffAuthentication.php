<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Illuminate\Support\Facades\Auth;

class StaffAuthentication
{
    /**
     * Hnadle an incoming request
     *
     *  @param \Illuminate\Http\Request $request
     *  @param \Closure $next
     *  @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if ($user) {
            if (!$user->hasRole('staff')) {
                abort(401, 'This action is unauthorized.');
            }
            
            return $next($request);
        } 
        else {
            if (!$request->ajax()) {
                if (!$request->json()) {
                    return redirect('/login')->with('fail', __('general.action_required', ['action' => 'Login']));
                } else {
                    return response()->json(["status" => "fail", "message" => 'Invalid role']);
                }
            } else {
                return response()->json(["status" => "fail", "message" => __('general.action_required', ['action' => __('general.login')])]);
            }
        }
    }
}
