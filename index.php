<?php

require 'vendor/autoload.php'; // Autoload dependencies

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();
require 'controllers/InvoicesController.php'; // 
require 'controllers/CompaniesController.php'; // 
require 'controllers/ContactsController.php'; //

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

// Get the last 2 invoices
$router->get('/invoices/last', function() use ($invoicesController) {
    $invoicesController->getLastInvoices();
});

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

// Instantiate the CompaniesController once
$companiesController = new CompaniesController($pdo);

// Define routes

//all companies
$router->get('/companies', function() use ($companiesController) {
    $companiesController->getAllCompanies();
});
//single company
$router->get('/companies/(\d+)', function($id) use ($companiesController) {
    $companiesController->getCompany($id);
});
//create company
$router->post('/companies', function() use ($companiesController) {
    $data = json_decode(file_get_contents('php://input'), true);
    $companiesController->createCompany($data);
});
//update company
$router->put('/companies/(\d+)', function($id) use ($companiesController) {
    $data = json_decode(file_get_contents('php://input'), true);
    $companiesController->updateCompany($id, $data);
});
//delete company
$router->delete('/companies/(\d+)', function($id) use ($companiesController) {
    $companiesController->deleteCompany($id);
});

//get last 2 companies
$router->get('/companies/last', function() use ($companiesController) {
    $companiesController->getLastCompanies();
});

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

// Instantiate the ContactsController once
$ContactsController = new ContactsController($pdo);

// Define routes
$router->get('/contacts', function() use ($ContactsController) {
    $ContactsController->getAllContacts();
});
$router->get('/contacts/(\d+)', function($id) use ($ContactsController) {
    $ContactsController->getContact($id);
});
$router->post('/contacts', function() use ($ContactsController) {
    $data = json_decode(file_get_contents('php://input'), true);
    $ContactsController->createContact($data);
});
$router->put('/contacts/(\d+)', function($id) use ($ContactsController) {
    $data = json_decode(file_get_contents('php://input'), true);
    $ContactsController->updateContact($id, $data);
});
$router->delete('/contacts/(\d+)', function($id) use ($ContactsController) {
    $ContactsController->deleteContact($id);
});

// Get the last 2 contacts
$router->get('/contacts/last', function() use ($ContactsController) {
    $ContactsController->getLastContacts();
});

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

// Fetching all invoices for a company
$router->get('/companies/(\d+)/invoices', function($id) use ($companiesController) {
    $companiesController->getCompanyInvoices($id);
});


// Run the router
$router->run();

?>