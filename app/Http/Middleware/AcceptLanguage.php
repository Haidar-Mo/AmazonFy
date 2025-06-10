<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class AcceptLanguage
{
    public function handle($request, Closure $next)
    {
        $locale = $request->query(
            'lang',
            $request->header('Accept-Language', 'en')
        );

        if (!in_array($locale, ['en', 'ar'])) {
            $locale = 'en';
        }

        App::setLocale($locale);

        return $next($request);
    }
}