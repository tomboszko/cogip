<?php

require_once __DIR__ . '/../models/ContactsModel.php';


class ContactsController {
    private $model;
    private $db;

    public function __construct($pdo) {
        $this->model = new ContactModel($pdo);
        $this->db = $pdo;
    }

    public function getAllContacts($currentPage) {
        $itemsPerPage = 5; // Set items per page to 5
    
        // Initialize Pagination object with current page and items per page
        $pagination = new Pagination($itemsPerPage, $currentPage);
    
        // Get contacts for the current page from the model
        $result = $this->model->getAllContacts($pagination);
    
        // Set Content-Type header for JSON response
        header('Content-Type: application/json');
    
        // Return the contacts and pagination info as JSON
        echo json_encode($result, JSON_PRETTY_PRINT);
    }

// fetch a single contact by id
    public function getContact($id) {
        try {
            $contact = $this->model->getContactById($id); // Correction ici
            header('Content-Type: application/json');
            if ($contact) { // Correction ici
                echo json_encode($contact); // Correction ici
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Contact not found']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'An error occurred while fetching the Contact']);
        }
    }
    

    public function createContact($data) {
        try {
            if (!isset($data['contact_name']) || !is_string($data['contact_name'])) {
                http_response_code(400);
                echo json_encode(['message' => 'contact_name']);
                return;
            }

            $ContactId = $this->model->createContacts($data);
            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode(['message' => 'Contact created', 'id' => $ContactId]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'An error occurred while creating the Contact']);
        }
    }

    public function updateContact($id, $data) {
        try {
            if (!isset($data['contact_name']) || !is_string($data['contact_name'])) {
                http_response_code(400);
                echo json_encode(['message' => 'Invalid contact_name']);
                return;
            }

            $result = $this->model->updateContact($id, $data);
            header('Content-Type: application/json');
            if ($result) {
                echo json_encode(['message' => 'Contact updated']);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Contact not found or no changes made']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'An error occurred while updating the Contact']);
        }
    }

    public function deleteContact($id) {
        try {
            $result = $this->model->deleteContact($id);
            header('Content-Type: application/json');
            if ($result) {
                echo json_encode(['message' => 'Contact deleted']);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Contact not found']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'An error occurred while deleting the Contact']);
        }
    }


}