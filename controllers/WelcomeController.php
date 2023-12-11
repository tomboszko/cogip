<?php

require_once __DIR__ . '/../models/WelcomeModel.php';

namespace Cogip\Controllers;

use Cogip\Models\WelcomeModel;

class WelcomeController {
    private $model;

    public function __construct($pdo) {
        $this->model = new WelcomeModel($pdo);
    }


public function getLastCompanies() {
        try {
            $companies = $this->model->getLastCompanies();
            $companies = array('Last companies' => $companies); // Wrap the companies array inside another array
            header('Content-Type: application/json');
            echo json_encode($companies, JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'An error occurred while fetching companies'], JSON_PRETTY_PRINT);
        }
    }

    public function getLastContacts() {
        try {
            $contacts = $this->model->getLastContacts();
            $contacts = array('Last contacts' => $contacts); // Wrap the contacts array inside another array
            header('Content-Type: application/json');
            echo json_encode($contacts, JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'An error occurred while fetching contacts'], JSON_PRETTY_PRINT);
        }
    }

    // Get the last 2 invoices
    public function getLastInvoices() {
        try {
            $invoices = $this->model->getLastInvoices();
            $invoices = array('last invoices' => $invoices); // Wrap the invoices array inside another array
            header('Content-Type: application/json');
            echo json_encode($invoices, JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'An error occurred while fetching invoices'], JSON_PRETTY_PRINT);
        }
    }
}
