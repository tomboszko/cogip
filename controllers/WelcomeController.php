<?php

require_once __DIR__ . '/../models/InvoicesModel.php';
require_once __DIR__ . '/../models/CompaniesModel.php';
require_once __DIR__ . '/../models/ContactsModel.php';
require_once __DIR__ . '/../models/WelcomeModel.php';
require_once __DIR__ . '/../models/ErrorModel.php';

class WelcomeController {
    private $model;

    public function __construct($pdo) {
        $this->model = new WelcomeModel($pdo);
    }


public function getLastCompanies() {
    $error = new ErrorModel();
        try {
            $companies = $this->model->getLastCompanies();
            $companies = array('Last companies' => $companies); // Wrap the companies array inside another array
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'status' => 200,
                'data' => $companies], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            $error->logError($e);
            $error->sendErrorResponse($e);
        }
    }

    public function getLastContacts() {
        $error = new ErrorModel();
        try {
            $contacts = $this->model->getLastContacts();
            $contacts = array('Last contacts' => $contacts); // Wrap the contacts array inside another array
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'status' => 200,
                'data' => $contacts], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            $error->logError($e);
            $error->sendErrorResponse($e);
        }
    }

    
    public function getLastInvoices() {
        $error = new ErrorModel();
        try {
            $invoices = $this->model->getLastInvoices();
            $invoices = array('last invoices' => $invoices); // Wrap the invoices array inside another array
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'status' => 200,
                'data' => $invoices], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            $error->logError($e);
            $error->sendErrorResponse($e);
        }
    }
}
