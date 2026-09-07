<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RestrictSpecgen
{
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('admin')->user();

        if ($user && $user->id == 3) {
    // Allow Products pages + variant routes
    if (
        !$request->is('admin/product*') &&
        !$request->is('admin/edit-variant*') &&
        !$request->is('admin/delete-variant') &&
        !$request->is('admin/update-variant*')
    ) {
        return redirect('admin/product');
    }
}


        return $next($request);
    }
}
