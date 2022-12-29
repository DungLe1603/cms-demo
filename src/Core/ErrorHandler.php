<?php

namespace App\Core;

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use Throwable;
use function json_encode;

class ErrorHandler
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        try {
            return $next($request);
        } catch (Throwable $error) {
            return new Response(
                500,
                ['Content-Type' => 'application/json'],
                json_encode(['message' => $error->getMessage()])
            );
        }
    }
}