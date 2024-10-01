<?php
$allowedIP =  "http://localhost:5173";
header('Access-Control-Allow-Origin: ' . $allowedIP);
header("Access-Control-Allow-Methods: GET, DELETE,");

use App\Controllers\ApiController;
use App\Database;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
$apiController = new ApiController;

//Controller ve Model kullanarak oluşturduğumuz routelar
$app->get('/', [$apiController, 'fetchApiDataDB']);
$app->get('/getData', [$apiController, 'getAllData']);
$app->delete('/posts/{id}', [$apiController, 'deletePost']);

$app->run();
