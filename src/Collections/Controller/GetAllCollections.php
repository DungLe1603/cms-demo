<?php

namespace App\Collections\Controller;

use Doctrine\DBAL\Connection;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use function var_dump;

class GetAllCollections
{
    public function __construct(private Connection $conn) {}

    public function __invoke(ServerRequestInterface $request)
    {
        $collections = $this->conn->createQueryBuilder()
            ->select('*')
            ->from('collections')
            ->fetchAllAssociative();

        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode(['message' => $collections])
        );
    }
}