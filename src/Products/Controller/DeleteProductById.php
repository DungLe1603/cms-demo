<?php

namespace App\Products\Controller;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

class DeleteProductById
{
    public function __construct(private Connection $conn)
    {
    }

    public function __invoke(ServerRequestInterface $request, int $id)
    {
        $this->conn->delete('products', ['id' => $id]);
        
        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode(['message' => 'delete request to /product/{id}'])
        );
    }
}