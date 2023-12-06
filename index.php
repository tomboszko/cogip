<?php

require 'vendor/autoload.php'; // Autoload dependencies
require 'controllers/InvoicesController.php'; // Require controller
require 'db.php'; // Require database

use Bramus\Router\Router;

$router = new Router();

// Instantiate the InvoicesController once
$invoicesController = new InvoicesController($pdo);

// Define routes 

// Fetching all invoices
$router->get('/invoices', function() use ($invoicesController) {
    $invoicesController->getAllInvoices();
});

// Fetching a single invoice
$router->get('/invoices/(\d+)', function($id) use ($invoicesController) {
    $invoicesController->getInvoice($id);
});

// Creating an invoice
$router->post('/invoices', function() use ($invoicesController) {
    // Assuming you're getting JSON input
    $data = json_decode(file_get_contents('php://input'), true);
    $invoicesController->createInvoice($data);
});

// Updating an invoice
$router->put('/invoices/(\d+)', function($id) use ($invoicesController) {
    $data = json_decode(file_get_contents('php://input'), true);
    $invoicesController->updateInvoice($id, $data);
});

// Deleting an invoice
$router->delete('/invoices/(\d+)', function($id) use ($invoicesController) {
    $invoicesController->deleteInvoice($id);
});

// Run the router
$router->run();

?>