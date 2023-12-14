<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Controllers\CompaniesController;
use App\Controllers\InvoicesController;
use App\Controllers\ContactsController;
use PDO;


class WelcomeController extends Controller

{
    private $companiesModel;
    private $invoicesModel;
    private $contactsModel;

    public function __construct()
    {
        $this->companiesModel = new CompaniesController;
        $this->invoicesModel = new InvoicesController;
        $this->contactsModel = new ContactsController;
    }

    public function getFirstFiveCompanies()
    {
        $this->companiesModel->getFirstFiveCompanies();
    }

    public function getFirstFiveInvoices()
    {
        $this->invoicesModel->getFirstFiveInvoices();
    }

    public function getFirstFiveContacts()
    {
        $this->contactsModel->getFirstFiveContacts();
    }
}