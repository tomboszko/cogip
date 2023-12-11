<?php

require_once __DIR__ . '/../models/InvoicesModel.php';
require_once __DIR__ . '/../models/CompaniesModel.php';
require_once __DIR__ . '/../models/ContactsModel.php';

class CompaniesController {
    private $model;
    private $db;

    public function __construct($pdo) {
        $this->model = new CompanyModel($pdo);
        $this->db = $pdo;
    }

    public function getAllCompanies($currentPage) {
        $itemsPerPage = 5; // Set items per page to 5
        // Initialize Pagination object with current page and items per page
        $pagination = new Pagination($itemsPerPage, $currentPage);
        // Get companies for the current page from the model
        $result = $this->model->getAllCompanies($pagination);
        // Set Content-Type header for JSON response
        header('Content-Type: application/json');
        // Return the companies and pagination info as JSON
        echo json_encode($result, JSON_PRETTY_PRINT);
    }




    public function getCompany($id) {
        try {
            $company = $this->model->getCompanyById($id);
            header('Content-Type: application/json');
            if ($company) {
                echo json_encode($company, JSON_PRETTY_PRINT);

            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Company not found']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'An error occurred while fetching the company']);
        }
    }

    public function createCompany($data) {
        try {
            if (!isset($data['name']) || !is_string($data['name'])) {
                http_response_code(400);
                echo json_encode(['message' => 'Invalid name']);
                return;
            }

            $companyId = $this->model->createCompany($data);
            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode(['message' => 'Company created', 'id' => $companyId]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'An error occurred while creating the company']);
        }
    }

    public function updateCompany($id, $data) {
        try {
            if (!isset($data['company_name']) || !is_string($data['company_name'])) {
                http_response_code(400);
                echo json_encode(['message' => 'Invalid company_name']);
                return;
            }

            $result = $this->model->updateCompany($id, $data);
            header('Content-Type: application/json');
            if ($result) {
                echo json_encode(['message' => 'Company updated']);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Company not found or no changes made']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'An error occurred while updating the company']);
        }
    }

    public function deleteCompany($id) {
        try {
            $result = $this->model->deleteCompany($id);
            header('Content-Type: application/json');
            if ($result) {
                echo json_encode(['message' => 'Company deleted']);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Company not found']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'An error occurred while deleting the company']);
        }
    }
}

