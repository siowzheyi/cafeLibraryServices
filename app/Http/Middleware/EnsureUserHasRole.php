<?php

namespace App\Http\Middleware;

use Closure;

class EnsureUserHasRole
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $user = $request->user();
        if (! in_array($user->roles()->first()->name, $roles)) {
            // Redirect...

            abort(401, 'This action is unauthorized.');
        }

        return $next($request);
    }
}
