<?php

namespace App\Core;

use FastRoute\Dispatcher;
use FastRoute\Dispatcher\GroupCountBased;
use FastRoute\RouteCollector;
use LogicException;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use function array_values;

class Router
{
    private $dispatcher;

    public function __construct(private RouteCollector $routes)
    {
        $this->dispatcher = new GroupCountBased($this->routes->getData());
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $routeInfo = $this->dispatcher->dispatch(
            $request->getMethod(), $request->getUri()->getPath()
        );
        // Dispatcher::NOT_FOUND`, `Dispatcher::METHOD_NOT_ALLOWED` and `Dispatcher::FOUND
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                return new Response(404, ['Content-Type' => 'text/plain'], 'Not found');

            case Dispatcher::METHOD_NOT_ALLOWED:
                return new Response(405, ['Content-Type' => 'text/plain'], 'Method not allowed');

            case Dispatcher::FOUND:
                $params = array_values($routeInfo[2]);
                return $routeInfo[1]($request, ...$params);

            default:
                throw new LogicException('Something went wrong with routing');
        }
    }
}