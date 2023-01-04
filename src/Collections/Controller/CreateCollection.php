<?php

namespace App\Collections\Controller;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use Exception;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use function json_decode;
use function json_encode;
use function var_dump;

class CreateCollection
{
    public function __construct(private Connection $conn) {}

    public function __invoke(ServerRequestInterface $request) {
        $platform = $this->conn->getDatabasePlatform();
        $schema = new Schema();

        $data = json_decode($request->getBody()->getContents(), TRUE);
        if (!$this->conn->createSchemaManager()->tablesExist($data['apiKey'])) {
            $this->createCollectionTable($schema, $platform, $data);
            $this->addCollection($data);
            return new Response(
                200,
                ['Content-Type' => 'application/json'],
                json_encode([
                    'name' => $data["displayName"],
                    'apiKey' => $data["apiKey"],
                ])
            );
        }

        return new Response(
            500,
            ['Content-Type' => 'application/json'],
            json_encode([
                'message' => "collection already exist",
            ])
        );
    }

    private function createCollectionTable(Schema $schema, AbstractPlatform $platform, array $data) {
        $myTable = $schema->createTable($data["apiKey"]);
        $myTable->addColumn("id", "integer", ["unsigned" => true, 'autoincrement' => true]);
        $myTable->setPrimaryKey(["id"]);
        foreach ($data["attributes"] as $key => $attribute) {
            $myTable->addColumn($key, $attribute["type"]);
        }
        $queries = $schema->toSql($platform);
        return $this->conn->executeQuery($queries[0]);
    }

    private function addCollection(array $data) {
        return $this->conn->insert('collections', [
            'name' => $data['displayName'],
            'api_key' => $data['apiKey']
        ]);
    }
}