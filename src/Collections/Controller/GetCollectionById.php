<?php

namespace App\Collections\Controller;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Exception;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use function count;
use function json_decode;
use function json_encode;
use function var_dump;

class GetCollectionById
{
    public function __construct(private Connection $conn) {}

    public function __invoke(ServerRequestInterface $request, string $apiKey)
    {
        try {
            $collection= $this->conn->createQueryBuilder()
                ->select('*')
                ->from('collections')
                ->where('api_key = "' . $apiKey . '"')
                ->fetchAssociative();

            $schema = $this->conn->createSchemaManager();
            $tableColumns =$schema->listTableColumns('collections');
            $columns = [];
            foreach ($tableColumns as $column) {
                $columns[$column->getName()] = $column->getType()->getName();
            }
            $collection['attributes'] = $columns;

            if (!$collection) {
                return new Response(
                    404,
                    ['Content-Type' => 'application/json'],
                    json_encode(['message' => 'collection not found'])
                );
            }
            return new Response(
                200,
                ['Content-Type' => 'application/json'],
                json_encode(['collection' => $collection])
            );
        } catch (Exception $e) {
            var_dump($e);
        }
    }
}