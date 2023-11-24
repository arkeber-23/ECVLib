<?php

namespace Kernel\Core\Middleware;

use Kernel\Core\Libs\Request;
use Kernel\Core\Libs\Response;

abstract class MiddlewareBase
{
    public function handleInput(Request $request)
    {
        return $request;
    }

    abstract public function process(Request $request, Response $response, $next);

    public function handleOutput(Response $response)
    {
        return $response;
    }

    public function handle(Request $request, $response, $next)
    {
        $request = $this->handleInput($request);
        $response = $this->process($request, $response, $next);
        $response = $this->handleOutput($response);

        return $response;
    }
}
