<?php

namespace App\Collections\Controller;

use Doctrine\DBAL\Connection;
use Exception;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use function date;
use function json_decode;
use function var_dump;

class UpdateCollectionAttributes
{
    public function __construct(private Connection $conn)
    {
    }

    public function __invoke(ServerRequestInterface $request, string $apiKey)
    {
        try {
            $schemaManager = $this->conn->createSchemaManager();
            $data = json_decode($request->getBody()->getContents(), TRUE);
            var_dump($data);
            var_dump($schemaManager->introspectTable('products'));
            $columns = $schemaManager->listTableColumns('user');

//            $this->conn->update($apiKey, $request->getParsedBody(), ['api_key' => $apiKey]);
//            $this->conn->update('collections',
//                [
//                    'updated_at' => date("Y-m-d H:i:s"),
//                ],
//                ['api_key' => $apiKey]
//            );

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