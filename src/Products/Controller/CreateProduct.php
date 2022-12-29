<?php

namespace App\Products\Controller;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use function json_decode;
use function var_dump;

class CreateProduct
{
    public function __construct(private Connection $conn)
    {
    }

    public function __invoke(ServerRequestInterface $request)
    {
        $this->conn->insert('products', [
                'name' => $request->getParsedBody()['name'],
                'price' => $request->getParsedBody()['price']
            ]);

        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode([
                'message' => 'Post request to /products'
            ])
        );
    }
}