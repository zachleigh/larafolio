<?php

namespace Larafolio\tests\stubs;

use Auth;
use Gate;
use Closure;

class ManagePortfolio
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
        $user = Auth::user();

        if (!$user || Gate::denies('manage-portfolio')) {
            return redirect('/');
        }

        return $next($request);
    }
}
