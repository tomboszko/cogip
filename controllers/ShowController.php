<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Model\CompaniesModel;
use App\Model\InvoicesModel;
use App\Model\ContactsModel;
use PDO;


class ShowController extends Controller
{
    private $companiesModel;
    private $invoicesModel;
    private $contactsModel;

    public function __construct()
    {
        $this->companiesModel = new CompaniesModel;
        $this->invoicesModel = new InvoicesModel;
        $this->contactsModel = new ContactsModel;
    }
    
    public function getCompanyById($id)
    {
        $this->companiesModel->getCompanyById($id);
    }

    public function getCompanyInvoices()
    {
        $this->invoicesModel->getCompanyInvoices();
    }

    public function getCompanyContacts()
    {
        $this->contactsModel->getCompanyContacts();
    }
}