<?php

namespace App\Products\Controller;

use App\Core\JsonResponse;
use App\Products\Product;
use App\Products\Storage;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Query\QueryBuilder;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use function count;
use function json_encode;
use function var_dump;

class GetAllProducts
{
    public function __construct(private QueryBuilder $queryBuilder)
    {
    }

    public function __invoke(ServerRequestInterface $request)
    {
        try {
            $products = $this->queryBuilder
                ->select('*')
                ->from('products')->execute()->fetchAll();

            return new Response(
                200,
                ['Content-Type' => 'application/json'],
                json_encode(['products' => $products])
            );
        } catch (\Throwable $e) {
            var_dump($e->getMessage());
        }

    }
}