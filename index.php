<?php
// allow CORS
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: GET, POST, DELETE, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-type, Authorization');
header('HTTP/1.1 200 OK');
header('Content-Type: application/json');


//install whoops error handler
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();
  
require 'vendor/autoload.php';
require 'controllers/InvoicesController.php'; // 
require 'controllers/CompaniesController.php'; // 
require 'controllers/ContactsController.php'; //
require 'controllers/WelcomeController.php'; //
require 'controllers/ShowController.php'; //
require 'db.php'; // 

use Bramus\Router\Router;

$router = new Router();


// Instantiate the welcome
//$welcomeController = new WelcomeController($pdo);

// // Define routes
// $router->get('/welcome', function() use ($welcomeController) {
//     $welcomeController->getLastCompanies();
//     $welcomeController->getLastContacts();
//     $welcomeController->getLastInvoices();
// });

// Instantiate the InvoicesController once
$invoicesController = new InvoicesController($pdo);
// Define routes 

// Fetching a single invoice
$router->get('/invoices/(\d+)', function($id) use ($invoicesController) {
    $invoicesController->getInvoice($id);
});

// Fetching all invoices with pagination
$router->get('/invoices', function() use ($invoicesController) {
    // Retrieve the 'page' query parameter, defaulting to 1 if not present
    // example : /invoices?page=3  show only page 3
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    // Call the controller method with the page number
    $invoicesController->getAllInvoices($page, 5); 
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

// Get the last 5 invoices
$router->get('/invoices/last', function() use ($invoicesController) {
    $invoicesController->getLastInvoices();
});

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

// Instantiate the CompaniesController once
$companiesController = new CompaniesController($pdo);
// Define routes
//all companies
$router->get('/companies', function() use ($companiesController) {
    //retrieve the 'page' query parameter, defaulting to 1 if not present
    //example : /companies?page=3  show only page 3
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    //call the controller method with the page number
    $companiesController->getAllCompanies($page, 5);
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

//get last 5 companies
$router->get('/companies/last', function() use ($companiesController) {
    $companiesController->getLastCompanies();
});

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

// Instantiate the ContactsController once
$ContactsController = new ContactsController($pdo);

// Define routes

// Fetching all contacts with pagination
$router->get('/contacts', function() use ($ContactsController) {
    // Retrieve the 'page' query parameter, defaulting to 1 if not present
    // example : /contacts?page=3  show only page 3
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    // Call the controller method with the page number
    $ContactsController->getAllContacts($page, 5);
});

// Fetching a single contact
$router->get('/contacts/(\d+)', function($id) use ($ContactsController) {
    $ContactsController->getContact($id);
});
// Creating a contact
$router->post('/contacts', function() use ($ContactsController) {
    $data = json_decode(file_get_contents('php://input'), true);
    $ContactsController->createContact($data);
});
// Updating a contact
$router->put('/contacts/(\d+)', function($id) use ($ContactsController) {
    $data = json_decode(file_get_contents('php://input'), true);
    $ContactsController->updateContact($id, $data);
});
// Deleting a contact
$router->delete('/contacts/(\d+)', function($id) use ($ContactsController) {
    $ContactsController->deleteContact($id);
});

// Get the last 5 contacts
$router->get('/contacts/last', function() use ($ContactsController) {
    $ContactsController->getLastContacts();
});

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

$ShowController = new ShowController($pdo);

// // Fetching all invoices for a company
// $router->get('/companies/(\d+)/invoices', function($id) use ($companiesController) {
//     $ShowController->getCompanyInvoices($id);
// });

// Fetching all contacts for a company
$router->get('/companies/(\d+)/show', function ($id) use ($ShowController) {
    $ShowController->getShowCompany($id);
});


$router->get('/api/routes', function() {
    $routes = [
        'Title' => 'cogip API',
        'Description' => 'This is the API for the Cogip application',
        'link' => 'https://cogip-api-8d6f281a9687.herokuapp.com/',
        'Endpoint' => [
            
        'Invoices' => [
            ['method' => 'GET', 'path' => '/invoices', 'description' => 'Fetch all invoices with pagination'],
            ['method' => 'GET', 'path' => '/invoices/{id}', 'description' => 'Fetch a single invoice by ID'],
            ['method' => 'POST', 'path' => '/invoices', 'description' => 'Create a new invoice'],
            ['method' => 'PUT', 'path' => '/invoices/{id}', 'description' => 'Update an existing invoice'],
            ['method' => 'DELETE', 'path' => '/invoices/{id}', 'description' => 'Delete an invoice'],
            ['method' => 'GET', 'path' => '/invoices/last', 'description' => 'Get the last 5 invoices'],
        ],
        'Companies' => [
            ['method' => 'GET', 'path' => '/companies', 'description' => 'Fetch all companies with pagination'],
            ['method' => 'GET', 'path' => '/companies/{id}', 'description' => 'Fetch a single company by ID'],
            ['method' => 'POST', 'path' => '/companies', 'description' => 'Create a new company'],
            ['method' => 'PUT', 'path' => '/companies/{id}', 'description' => 'Update an existing company'],
            ['method' => 'DELETE', 'path' => '/companies/{id}', 'description' => 'Delete a company'],
            ['method' => 'GET', 'path' => '/companies/last', 'description' => 'Get the last 5 companies'],
        ],
        'Contacts' => [
            ['method' => 'GET', 'path' => '/contacts', 'description' => 'Fetch all contacts with pagination'],
            ['method' => 'GET', 'path' => '/contacts/{id}', 'description' => 'Fetch a single contact by ID'],
            ['method' => 'POST', 'path' => '/contacts', 'description' => 'Create a new contact'],
            ['method' => 'PUT', 'path' => '/contacts/{id}', 'description' => 'Update an existing contact'],
            ['method' => 'DELETE', 'path' => '/contacts/{id}', 'description' => 'Delete a contact'],
            ['method' => 'GET', 'path' => '/contacts/last', 'description' => 'Get the last 5 contacts'],
        ]
    ]
        ];


    header('Content-Type: application/json');
    echo json_encode($routes, JSON_PRETTY_PRINT);
});






// Run the router
$router->run();

?>