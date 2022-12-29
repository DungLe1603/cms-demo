<?php

namespace App\Collections\Controller;

use Doctrine\DBAL\Connection;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use function count;
use function json_encode;
use function var_dump;

class GetCollectionById
{
    public function __construct(private Connection $conn) {}

    public function __invoke(ServerRequestInterface $request, int $id)
    {
        $collection= $this->conn->createQueryBuilder()
            ->select('*')
            ->from('collections')
            ->where('id = ' . $id)
            ->fetchAssociative();

        if ($collection) {
            return new Response(
                200,
                ['Content-Type' => 'application/json'],
                json_encode(['collection' => $collection])
            );
        } else {
            return new Response(
                404,
                ['Content-Type' => 'application/json'],
                json_encode(['message' => 'collection not found'])
            );
        }
    }
}