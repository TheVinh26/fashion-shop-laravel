<?php

namespace App\Exceptions;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

use Exception;

class Handler extends Exception
{
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ThrottleRequestsException) {

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'You have entered the wrong password more than 10 times in 1 minute. Please try again later.',
                ])
                ->withInput($request->except('password'));
        }

        return parent::render($request, $exception);
    }   
}
