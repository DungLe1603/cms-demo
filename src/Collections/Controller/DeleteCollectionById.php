<?php

namespace App\Collections\Controller;

use Doctrine\DBAL\Connection;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use function json_encode;
use function var_dump;

class DeleteCollectionById
{
    public function __construct(private Connection $conn) {}

    public function __invoke(ServerRequestInterface $request, string $apiKey)
    {
        $fromSchema = $this->conn->createSchemaManager();
        if ($this->conn->createSchemaManager()->tablesExist($apiKey)) {
            $toSchema = clone $fromSchema;
            $toSchema->dropTable($apiKey);
            $this->conn->delete('collections', ['api_key' => $apiKey]);
            return new Response(
                200,
                ['Content-Type' => 'application/json'],
                json_encode(['message' => 'collection is deleted'])
            );
        }
        return new Response(
            500,
            ['Content-Type' => 'application/json'],
            json_encode(['message' => 'collection not found'])
        );
    }
}