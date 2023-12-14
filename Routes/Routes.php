<?php

namespace App\Routes;

use Bramus\Router\Router;
use App\Controllers\WelcomeController;
use App\Controllers\InvoicesController;
use App\Controllers\ContactsController;
use App\Controllers\CompaniesController;
use App\Controllers\ShowController;

// Instanciation du routeur
$router = new Router();

// Instanciation des contrôleurs au début
$welcomeController = new WelcomeController();
$invoicesController = new InvoicesController();
$contactsController = new ContactsController();
$companiesController = new CompaniesController();
$showController = new ShowController();

// Route pour la page d'accueil
$router->get('/', function () use ($welcomeController) {
    // Exécute les méthodes du WelcomeController pour obtenir les données nécessaires
    $welcomeController->getFirstFiveCompanies();
    $welcomeController->getFirstFiveInvoices();
    $welcomeController->getFirstFiveContacts();
});

// Routes pour les factures
$router->get('/invoices', function () use ($invoicesController) {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

    $invoicesController->getAllInvoices($page, $perPage);
});

$router->get('/invoices/(\d+)', function ($id) use ($invoicesController) {
    $invoicesController->getInvoiceById($id);
});

$router->post('/invoices', function () use ($invoicesController) {
    // Récupération des données JSON de la requête POST
    $data = json_decode(file_get_contents('php://input'), true);
    $invoicesController->createInvoice($data);
});

// Routes pour les contacts
$router->get('/contacts', function () use ($contactsController) {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

    $contactsController->getAllContacts($page, $perPage);
});


$router->get('/contacts/(\d+)', function ($id) use ($contactsController) {
    $contactsController->getContactById($id);
});

$router->post('/contacts', function () use ($contactsController) {
    // Récupération des données JSON de la requête POST
    $data = json_decode(file_get_contents('php://input'), true);
    $contactsController->createContact($data);
});

// Routes pour les entreprises
$router->get('/companies', function () use ($companiesController) {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

    $companiesController->getAllCompanies($page, $perPage);
});


$router->get('/companies/(\d+)', function ($id) use ($companiesController) {
    $companiesController->getCompanyById($id);
});

$router->post('/companies', function () use ($companiesController) {
    // Récupération des données JSON de la requête POST
    $data = json_decode(file_get_contents('php://input'), true);
    $companiesController->createCompany($data);
});

// Route pour afficher des détails sur une entreprise spécifique
$router->get('/companies/(\d+)/show', function ($id) use ($showController) {
    // Exécute les méthodes du ShowController pour obtenir les données nécessaires
    $showController->getCompanyById($id);
    $showController->getCompanyInvoices($id);
    $showController->getCompanyContacts($id);
});

// Exécute le routeur
$router->run();
