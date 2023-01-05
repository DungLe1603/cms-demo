<?php

namespace App\Collections\Controller;

use Doctrine\DBAL\Connection;
use Exception;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use function json_encode;
use function var_dump;

class UpdateCollectionById
{
    public function __construct(private Connection $conn)
    {
    }

    public function __invoke(ServerRequestInterface $request, string $apiKey)
    {
        try {
            $collection= $this->conn->createQueryBuilder()
                ->select('*')
                ->from('collections')
                ->where('api_key = "' . $apiKey . '"')
                ->fetchAssociative();
            $schemaManager = $this->conn->createSchemaManager();
            if ($collection) {
                $collectionName = $request->getParsedBody()['name'];
                $schemaManager->renameTable($collection['name'], $collectionName);

                $this->conn->update('collections',
                    [
                        'name' => $collectionName,
                    ],
                    ['api_key' => $apiKey]
                );
            }

            return new Response(
                200,
                ['Content-Type' => 'application/json'],
                json_encode(['message' => 'update successfully'])
            );
        } catch (Exception $e) {
            return new Response(
                500,
                ['Content-Type' => 'application/json'],
                json_encode(['message' => $e->getMessage()])
            );
        }
    }
}