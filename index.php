<?php
require __DIR__ . '/vendor/autoload.php';

use App\Collections\Controller\CreateCollection;
use App\Collections\Controller\DeleteCollectionById;
use App\Collections\Controller\GetAllCollections;
use App\Collections\Controller\GetCollectionById;
use App\Collections\Controller\UpdateCollectionAttributes;
use App\Collections\Controller\UpdateCollectionById;
use App\Products\Controller\CreateProduct;
use App\Products\Controller\DeleteProductById;
use App\Products\Controller\GetAllProducts;
use App\Products\Controller\GetProductById;
use App\Products\Controller\UpdateProductById;
use Doctrine\DBAL\DriverManager;
use Dotenv\Dotenv;
use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser\Std;

$env = Dotenv::createImmutable(__DIR__);
$env->load();

$loop = React\EventLoop\Factory::create();

$connectionParams = [
    'dbname' => $_ENV['DB_NAME'],
    'user' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASS'],
    'host' => $_ENV['DB_HOST'],
    'driver' => $_ENV['DB_DRIVER'],
];
$conn = DriverManager::getConnection($connectionParams);
$queryBuilder = $conn->createQueryBuilder();

$routes = new RouteCollector(new Std(), new GroupCountBased());
//$routes->get('/products', new GetAllProducts($queryBuilder));
//$routes->get('/product/{id:\d+}', new GetProductById($queryBuilder));
//$routes->post('/products', new CreateProduct($conn));
//$routes->put('/product/{id:\d+}', new UpdateProductById($conn));
//$routes->delete('/product/{id:\d+}', new DeleteProductById($conn));

$routes->get('/collections', new GetAllCollections($conn));
$routes->post('/collections', new CreateCollection($conn));
$routes->get('/collections/{apiKey}', new GetCollectionById($conn));
$routes->delete('/collections/{apiKey}', new DeleteCollectionById($conn));
$routes->put('/collections/{apiKey}', new UpdateCollectionById($conn));
$routes->put('/collections/{apiKey}/attributes', new UpdateCollectionAttributes($conn));

$server = new \React\Http\Server(new \App\Core\Router($routes));

$socket = new React\Socket\SocketServer('127.0.0.1:8080');
$server->listen($socket);
$server->on('error', function (Throwable $error) {
    echo 'Error: ' . $error->getMessage() . PHP_EOL;
});

echo "Server running at" . $socket->getAddress() . PHP_EOL;
$loop->run();
