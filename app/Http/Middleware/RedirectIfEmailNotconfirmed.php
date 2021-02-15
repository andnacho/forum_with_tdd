<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfEmailNotconfirmed
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
        if (! $request->user()->hasVerifiedEmail()) {
            return redirect('/threads')
                ->with([
                    'flash' => 'You must first confirm you email address', 'warning',
                    'type' => 'warning',
                ]);
        }

        return $next($request);
    }
}
