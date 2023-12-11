<?php

require_once __DIR__ . '/../models/ShowModel.php';

//\\//\\JE N'AI PAS FAIT LA GESTION DES ERREURS ( a discuter avec l'équipe) \\//\\//

class ShowController {
    private $model;

    public function __construct($pdo) {
        $this->model = new ShowModel($pdo);
    }
// Get all invoices for a company
    public function getCompanyInvoices($id) {

            $invoices = $this->model->getCompanyInvoices($id);
            $invoices = array('Company invoices' => $invoices); // Wrap the invoices array inside another array
            header('Content-Type: application/json');
            echo json_encode($invoices, JSON_PRETTY_PRINT);

    }

    // Get all Contacts for a company
    public function getCompanyContacts($id) {

            $contacts = $this->model->getCompanyContacts($id);
            $contacts = array('Company contacts' => $contacts); // Wrap the Contacts array inside another array
            header('Content-Type: application/json');
            echo json_encode($contacts, JSON_PRETTY_PRINT);
    }

    public function getshowCompany($id) {

        $invoices = $this->model->getCompanyInvoices($id);
        $contacts = $this->model->getCompanyContacts($id);
        $companies = $this->model->getShowCompanyById($id);
        $company = array('Companies' => $companies, 'Invoices' => $invoices, 'Contacts' => $contacts); // Wrap the invoices array inside another array
        header('Content-Type: application/json');
        echo json_encode($company, JSON_PRETTY_PRINT);
    }
}