<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateJsonApiDocument
{
    public function handle(Request $request, Closure $next)
    {
        if($request->isMethod('POST') || $request->isMethod('PATCH')){
            $request -> validate([
                'data' => ['required', 'array'],
                'data.type' => ['required','string'],
                'data.attributes' => ['required','array']

                ]);
        }

        if($request->isMethod('PATCH')){
            $request -> validate([
                'data.id' =>['required','string']
            ]);
        }
        return $next($request);
    }
}
