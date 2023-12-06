<?php

require 'vendor/autoload.php'; // Autoload dependencies
require 'controllers/InvoicesController.php'; // Require controller

use Bramus\Router\Router;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = getenv('DB_HOST');
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$pdo = new PDO($dsn, $user, $pass, $options);
$router = new Router();

// Define routes 

//fetching all invoices
$router->get('/invoices', function() use ($pdo) {
    $controller = new InvoicesController($pdo);
    $controller->getAllInvoices();
});

//fetching a single invoice
$router->get('/invoices/(\d+)', function($id) use ($pdo) {
    $controller = new InvoicesController($pdo);
    $controller->getInvoice($id);
});

//  creating an invoice
$router->post('/invoices', function() use ($pdo) {
    // Assuming you're getting JSON input
    $data = json_decode(file_get_contents('php://input'), true);
    $controller = new InvoicesController($pdo);
    $controller->createInvoice($data);
});

// updating an invoice
$router->put('/invoices/(\d+)', function($id) use ($pdo) {
    $data = json_decode(file_get_contents('php://input'), true);
    $controller = new InvoicesController($pdo);
    $controller->updateInvoice($id, $data);
});

//deleting an invoice
$router->delete('/invoices/(\d+)', function($id) use ($pdo) {
    $controller = new InvoicesController($pdo);
    $controller->deleteInvoice($id);
});

// Run the router
$router->run();

?>