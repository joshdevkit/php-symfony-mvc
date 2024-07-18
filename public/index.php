<?php


require_once __DIR__ . '/../vendor/autoload.php';

define('RESOURCE_PATH', dirname(__DIR__) . '/resources/views/');
define('BASE_URL', 'http://localhost/php-psr4/public');
define('BASE_PATH', dirname(__DIR__));

use Core\Databases\Config;
use Core\Databases\Database;
use Core\Kernel;
use Core\Request;

Config::init();

$pdo = Database::connect();
\Core\Databases\Database\Model::init($pdo);
$request = Request::createFromGlobals();

$kernel = new Kernel();
$kernel->handleRequest($request);
