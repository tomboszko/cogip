<?php

namespace App\Routes;

use Bramus\Router\Router;
use App\Controllers\WelcomeController;
use App\Controllers\InvoicesController;
use App\Controllers\ContactsController;
use App\Controllers\CompaniesController;
use App\Controllers\ShowController;


$router = new Router();

$router->get('/', function() {
    (new WelcomeController)->index();
});

$router->run();

$router->get('/invoices', function() {
    (new InvoicesController)->getAllInvoices();
});

$router->get('/invoices/(\d+)', function($id) {
    (new InvoicesController)->getInvoice($id);
});

$router->post('/invoices', function() {
    $data = json_decode(file_get_contents('php://input'), true);
    (new InvoicesController)->createInvoice($data);
});

$router->get('/contacts', function() {
    (new ContactsController)->getAllContacts();
});

$router->get('/contacts/(\d+)', function($id) {
    (new ContactsController)->getContact($id);
});

$router->post('/contacts', function() {
    $data = json_decode(file_get_contents('php://input'), true);
    (new ContactsController)->createContact($data);
});

$router->get('/companies', function() {
    (new CompaniesController)->getAllCompanies();
});

$router->get('/companies/(\d+)', function($id) {
    (new CompaniesController)->getCompany($id);
});

$router->post('/companies', function() {
    $data = json_decode(file_get_contents('php://input'), true);
    (new CompaniesController)->createCompany($data);
});

$router->get('/companies/(\d+)/show', function($id) {
    (new ShowController)->getshowCompany($id);
});
