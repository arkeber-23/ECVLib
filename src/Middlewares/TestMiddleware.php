<?php

namespace App\Middlewares;

use Kernel\Core\Libs\Request;
use Kernel\Core\Libs\Response;
use Kernel\Core\Middleware\MiddlewareBase;

class TestMiddleware extends MiddlewareBase
{

        public function handleInput(Request $request)
        {

            return $request;
        }
        public function process(Request $request, Response $response, $next)
        {
            return $next($request, $response);
        }

        public function handleOutput(Response $response)
        {

            return $response;
        }
}