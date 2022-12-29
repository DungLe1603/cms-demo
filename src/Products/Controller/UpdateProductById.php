<?php

namespace App\Products\Controller;

use Doctrine\DBAL\Connection;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use function json_encode;
use function var_dump;

class UpdateProductById
{
    public function __construct(private Connection $conn)
    {
    }

    public function __invoke(ServerRequestInterface $request, int $id)
    {
        $this->conn->update('products',
            [
                'name' => $request->getParsedBody()['name'],
                'price' => $request->getParsedBody()['price']
            ],
            ['id' => 2]
        );

        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode(['product' => 'update successfully'])
        );
    }
}