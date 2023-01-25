<?php

namespace Larazoul\Larazoul\Larazoul\Middelware;

use Closure;
use Larazoul\Larazoul\Larazoul\Helpers\ParseInputStream;

class ParseMultipartFormDataInputForNonPostRequests
{
    /*
     * Content-Type: multipart/form-data - only works for POST requests. All others fail, this is a bug in PHP since 2011.
     * See comments here: https://github.com/laravel/framework/issues/13457
     *
     * This middleware converts all multi-part/form-data for NON-POST requests, into a properly formatted
     * request variable for Laravel 5.6. It uses the ParseInputStream class, found here:
     * https://gist.github.com/devmycloud/df28012101fbc55d8de1737762b70348
     */
    public function handle($request, Closure $next)
    {
        if ($request->method() == 'POST' OR $request->method() == 'GET') {
            return $next($request);
        }

        if (preg_match('/multipart\/form-data/', $request->headers->get('Content-Type')) or
            preg_match('/multipart\/form-data/', $request->headers->get('content-type'))
        ) {
            $params = array();
            new ParseInputStream($params);
            $request->request->add($params);
        }

        return $next($request);
    }
}