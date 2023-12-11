<?php

require_once __DIR__ . '/../models/InvoicesModel.php';
require_once __DIR__ . '/../models/CompaniesModel.php';
require_once __DIR__ . '/../models/ContactsModel.php';
require_once __DIR__ . '/../models/ErrorModel.php';

class CompaniesController {
    private $model;
    

    public function __construct($pdo) {
        $this->model = new CompanyModel($pdo);
        
    }

    public function getAllCompanies($currentPage) {
        try {
            $itemsPerPage = 5; // Set items per page to 5
            // Initialize Pagination object with current page and items per page
            $pagination = new Pagination($itemsPerPage, $currentPage);
            // Get companies for the current page from the model
            $result = $this->model->getAllCompanies($pagination);
            // Set Content-Type header for JSON response
            header('Content-Type: application/json');
            // Set the HTTP status code to 200 (OK)
            http_response_code(200);

            // Return the companies and pagination info as JSON
            echo json_encode([
                'status' => 200, 
                'data' => $result], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            $errorModel = new ErrorModel();
            $errorModel->logError($e);
            $errorModel->sendErrorResponse($e);
        }
    }


    public function getCompany($id) {
        $errorModel = new ErrorModel();
        try {
            $company = $this->model->getCompanyById($id);
            header('Content-Type: application/json');
            if ($company) {
                http_response_code(200);
                echo json_encode([
                    'status' => 200, 
                     'data' => $company], JSON_PRETTY_PRINT);
            } else {
                http_response_code(404);
                echo json_encode([
                    'status' => 404, 
                    'message' => 'Company not found']);
            }
        } catch (Exception $e) {
            // Use the ErrorModel to log the error and send an error response
            $errorModel->logError($e);
            $errorModel->sendErrorResponse($e);
        }
    }

    public function createCompany($data) {
        $errorModel = new ErrorModel(); // Instantiate the ErrorModel
        try {
            if (!isset($data['name']) || !is_string($data['name'])) {
                http_response_code(400);
                echo json_encode(['message' => 'Invalid name']);
                return;
            }

            $companyId = $this->model->createCompany($data);
            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode([
                'status' => 201,
                'message' => 'Company created', 
                'id' => $companyId]);
        } catch (Exception $e) {
            // Use the ErrorModel to log the error and send an error response
            $errorModel->logError($e);
            $errorModel->sendErrorResponse($e);
        }
    }

    public function updateCompany($id, $data) {
        $errorModel = new ErrorModel(); // Instantiate the ErrorModel
        try {
            if (!isset($data['company_name']) || !is_string($data['company_name'])) {
                http_response_code(400);
                echo json_encode([
                    'status' => 400,
                    'message' => 'Invalid company_name']);
                return;
            }

            $result = $this->model->updateCompany($id, $data);
            header('Content-Type: application/json');
            if ($result) {
                echo json_encode([
                    'status' => 200,
                    'message' => 'Company updated']);
            } else {
                http_response_code(404);
                echo json_encode([
                    'status' => 404,
                    'message' => 'Company not found or no changes made']);
            }
        } catch (Exception $e) {
            // Use the ErrorModel to log the error and send an error response
            $errorModel->logError($e);
            $errorModel->sendErrorResponse($e);
        }
    }

    public function deleteCompany($id) {
        $errorModel = new ErrorModel(); // Instantiate the ErrorModel
        try {
            $result = $this->model->deleteCompany($id);
            header('Content-Type: application/json');
            if ($result) {
                echo json_encode([
                    'status' => 200,
                    'message' => 'Company deleted']);
            } else {
                http_response_code(404);
                echo json_encode([
                    'status' => 404,
                    'message' => 'Company not found']);
            }
        } catch (Exception $e) {
            // Use the ErrorModel to log the error and send an error response
            $errorModel->logError($e);
            $errorModel->sendErrorResponse($e);
        }
    }
}

