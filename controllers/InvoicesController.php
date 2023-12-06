<?php

require_once __DIR__ . '/../models/InvoicesModel.php';

class InvoicesController {
    private $model;

    public function __construct($pdo) {
        $this->model = new InvoiceModel($pdo);
    }

    public function getAllInvoices() {
        try {
            $invoices = $this->model->getAllInvoices();
            header('Content-Type: application/json');
            echo json_encode($invoices, JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'An error occurred while fetching invoices'], JSON_PRETTY_PRINT);
        }
    }

    public function getInvoice($id) {
        try {
            $invoice = $this->model->getInvoiceById($id);
            header('Content-Type: application/json');
            if ($invoice) {
                echo json_encode($invoice);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Invoice not found']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'An error occurred while fetching the invoice']);
        }
    }

    public function createInvoice($data) {
        try {
            if (!isset($data['invoice_number']) || !is_string($data['invoice_number'])) {
                http_response_code(400);
                echo json_encode(['message' => 'Invalid invoice_number']);
                return;
            }

            $invoiceId = $this->model->createInvoice($data);
            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode(['message' => 'Invoice created', 'id' => $invoiceId]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'An error occurred while creating the invoice']);
        }
    }

    public function updateInvoice($id, $data) {
        try {
            if (!isset($data['invoice_number']) || !is_string($data['invoice_number'])) {
                http_response_code(400);
                echo json_encode(['message' => 'Invalid invoice_number']);
                return;
            }

            $result = $this->model->updateInvoice($id, $data);
            header('Content-Type: application/json');
            if ($result) {
                echo json_encode(['message' => 'Invoice updated']);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Invoice not found or no changes made']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'An error occurred while updating the invoice']);
        }
    }

    public function deleteInvoice($id) {
        try {
            $result = $this->model->deleteInvoice($id);
            header('Content-Type: application/json');
            if ($result) {
                echo json_encode(['message' => 'Invoice deleted']);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Invoice not found']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'An error occurred while deleting the invoice']);
        }
    }
}