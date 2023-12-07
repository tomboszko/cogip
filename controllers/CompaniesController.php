<?php

require_once __DIR__ . '/../models/CompaniesModel.php';

class CompaniesController {
    private $model;

    public function __construct($pdo) {
        $this->model = new CompanyModel($pdo);
    }

    public function getAllCompanies() {
        try {
            $companies = $this->model->getAllCompanies();
            $companies = array('companies' => $companies); // Wrap the companies array inside another array
            header('Content-Type: application/json');
            echo json_encode($companies, JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'An error occurred while fetching companies'], JSON_PRETTY_PRINT);
        }
    }

    public function getCompany($id) {
        try {
            $company = $this->model->getCompanyById($id);
            header('Content-Type: application/json');
            if ($company) {
                echo json_encode($company);
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
