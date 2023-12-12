<?php

require_once __DIR__ . '/../models/ContactsModel.php';
require_once __DIR__ . '/../models/ErrorModel.php';

class ContactsController {
    private $model;
    

    public function __construct($pdo) {
        $this->model = new ContactModel($pdo);
        
    }

    public function getAllContacts($currentPage) {
            $errorModel = new ErrorModel();
        try {
            $itemsPerPage = 5; // Set items per page to 5
            // Initialize Pagination object with current page and items per page
            $pagination = new Pagination($itemsPerPage, $currentPage);
            // Get contacts for the current page from the model
            $result = $this->model->getAllContacts($pagination);
            // Set Content-Type header for JSON response
            header('Content-Type: application/json');
            // Return the contacts and pagination info as JSON
            echo json_encode([
                'status' => 200, 
                'data' => $result], JSON_PRETTY_PRINT);
        } catch (Exception $e) {

            $errorModel->logError($e);
            $errorModel->sendErrorResponse($e);
        }
    }




    // fetch a single contact by id
    public function getContact($id) {
        $errorModel = new ErrorModel();
        try {
            $contact = $this->model->getContactById($id); // Correction ici
            header('Content-Type: application/json');
            if ($contact) { // Correction ici
                echo json_encode($contact); // Correction ici
            } else {
                http_response_code(404);
                echo json_encode([
                    'status' => 404,
                    'message' => 'Contact not found']);
            }
        } catch (Exception $e) {
            $errorModel->logError($e);
            $errorModel->sendNotFoundResponse($e);
        }
    }
    
    public function createContact($data) {
        $errorModel = new ErrorModel();
        try {
            if (!isset($data['contact_name']) || !is_string($data['contact_name'])) {
                http_response_code(400);
                echo json_encode(['message' => 'contact_name']);
                return;
            }

            $ContactId = $this->model->createContacts($data);
            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode([
                'status' => 201,
                'message' => 'Contact created', 
                'id' => $ContactId]);
        } catch (Exception $e) {
            $errorModel->logError($e);
            $errorModel->sendErrorResponse($e);

        }
    }

    public function updateContact($id, $data) {
        $errorModel = new ErrorModel();
        try {
            if (!isset($data['contact_name']) || !is_string($data['contact_name'])) {
                http_response_code(400);
                echo json_encode([
                    'status' => 400,
                    'message' => 'Invalid contact_name']);
                return;
            }

            $result = $this->model->updateContact($id, $data);
            header('Content-Type: application/json');
            if ($result) {
                echo json_encode([
                    'status' => 200,
                    'message' => 'Contact updated']);
            } else {
                http_response_code(404);
                echo json_encode([
                    'status' => 404,
                    'message' => 'Contact not found or no changes made']);
            }
        } catch (Exception $e) {
            $errorModel->logError($e);
            $errorModel->sendNotFoundResponse($e);
        }
    }

    public function deleteContact($id) {
        $errorModel = new ErrorModel();
        try {
            $result = $this->model->deleteContact($id);
            header('Content-Type: application/json');
            if ($result) {
                echo json_encode([
                    'status' => 200,
                    'message' => 'Contact deleted']);
            } else {
                http_response_code(404);
                echo json_encode([
                    'status' => 404,
                    'message' => 'Contact not found']);
            }
        } catch (Exception $e) {
            $errorModel->logError($e);
            $errorModel->sendNotFoundResponse($e);
        }
    }

    public function getLastContacts() {
        $errorModel = new ErrorModel();
        try {
            $result = $this->model->getLastContacts();
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 200, 
                'data' => $result], JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            $errorModel->logError($e);
            $errorModel->sendErrorResponse($e);
        }
    }
}