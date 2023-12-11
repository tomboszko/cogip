<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/InvoicesModel.php';
require_once __DIR__ . '/../models/Pagination.php';
require_once __DIR__ . '/../models/ErrorModel.php';

class InvoicesController {
    private $model;

    public function __construct($pdo) {
        $this->model = new InvoiceModel($pdo);
    }

    public function getAllInvoices($currentPage) {
        $errorModel = new ErrorModel();
        try {
            $itemsPerPage = 5; // Set items per page to 5
            // Initialize Pagination object with current page and items per page
            $pagination = new Pagination($itemsPerPage, $currentPage);
            // Get invoices for the current page from the model
            $result = $this->model->getAllInvoices($pagination);
            // Set Content-Type header for JSON response
            header('Content-Type: application/json');
            // Return the invoices and pagination info as JSON
            echo json_encode([
                'status' => 200, 
                'data' => $result], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            // Use the ErrorModel to log the error and send an error response
            $errorModel->logError($e);
            $errorModel->sendErrorResponse($e);
        }
    }
    

    public function getInvoice($id) {
        $errorModel = new ErrorModel();
        try {
            $invoice = $this->model->getInvoiceById($id);
            $invoice = array('Invoice' => $invoice); // Wrap the invoice array inside another array
            header('Content-Type: application/json');
            if ($invoice) {
                http_response_code(200);
                echo json_encode([
                    'status' => 200, 
                    'data' => $invoice], JSON_PRETTY_PRINT);
            } else {
                http_response_code(404);
                echo json_encode([
                    'status' => 404,
                    'message' => 'Invoice not found']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'An error occurred while fetching the invoice']);
        }
    }

    public function createInvoice($data) {
        $errorModel = new ErrorModel();
        try {
            if (!isset($data['invoice_number']) || !is_string($data['invoice_number'])) {
                http_response_code(400);
                echo json_encode(['message' => 'Invalid invoice_number']);
                return;
            }

            $invoiceId = $this->model->createInvoice($data);
            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode([
                'status' => 201,
                'message' => 'Invoice created', 
                'id' => $invoiceId]);
        } catch (Exception $e) {
            $errorModel->logError($e);
            $errorModel->sendErrorResponse($e);
        }
    }

    public function updateInvoice($id, $data) {
        $errorModel = new ErrorModel();
        try {
            if (!isset($data['invoice_number']) || !is_string($data['invoice_number'])) {
                http_response_code(400);
                echo json_encode([
                    'status' => 400,
                    'message' => 'Invalid invoice_number']);
                return;
            }

            $result = $this->model->updateInvoice($id, $data);
            header('Content-Type: application/json');
            if ($result) {
                echo json_encode([
                    'status' => 200,
                    'message' => 'Invoice updated']);
            } else {
                http_response_code(404);
                echo json_encode([
                    'status' => 404,
                    'message' => 'Invoice not found or no changes made']);
            }
        } catch (Exception $e) {
            $errorModel->logError($e);
            $errorModel->sendErrorResponse($e);
        }
    }

    public function deleteInvoice($id) {
        $errorModel = new ErrorModel();
        try {
            $result = $this->model->deleteInvoice($id);
            header('Content-Type: application/json');
            if ($result) {
                echo json_encode([
                    'status' => 200,
                    'message' => 'Invoice deleted']);
            } else {
                http_response_code(404);
                echo json_encode([
                    'status' => 404,
                    'message' => 'Invoice not found']);
            }
        } catch (Exception $e) {
            $errorModel->logError($e);
            $errorModel->sendErrorResponse($e);
        }
    }

}