<?php

namespace Larazoul\Larazoul\Larazoul\Middelware;

use Closure;
use Illuminate\Support\Facades\Auth;

class adminMiddleWare
{
    public function handle($request, Closure $next)
    {

        if(auth()->guest()){

            return redirect('/');
        }

        if(auth()->user()->group_id != 1){

            return redirect('/');

        }

       return $next($request) ;
    }
}