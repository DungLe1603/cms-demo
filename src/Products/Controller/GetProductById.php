<?php

namespace App\Products\Controller;

use Doctrine\DBAL\Query\QueryBuilder;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use function json_encode;
use function var_dump;

class GetProductById
{
    public function __construct(private QueryBuilder $queryBuilder)
    {
    }

    public function __invoke(ServerRequestInterface $request, int $id)
    {
        $product= $this->queryBuilder
            ->select('*')
            ->from('products')
            ->where('id = ' . $id)
            ->fetchAllAssociative();

        if (count($product) > 0) {
            return new Response(
                200,
                ['Content-Type' => 'application/json'],
                json_encode(['product' => $product[0]])
            );
        } else {
            return new Response(
                404,
                ['Content-Type' => 'application/json'],
                json_encode(['message' => 'product not found'])
            );
        }
    }
}