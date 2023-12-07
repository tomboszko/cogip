<?php

require_once __DIR__ . '../models/ContactsModel.php';

class ContactsController {
    private $model;

    public function __construct($pdo) {
        $this->model = new ContactModel($pdo);
    }

    public function getAllContacts() {
        try {
            $contacts = $this->model->getAllContacts();
            $contacts = array('contacts' => $contacts); // Wrap the contacts array inside another array
            header('Content-Type: application/json');
            echo json_encode($contacts, JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'An error occurred while fetching contacts'], JSON_PRETTY_PRINT);
        }
    }

    public function getContact($id) {
        try {
            $Contact = $this->model->getContactById($id);
            header('Content-Type: application/json');
            if ($Contact) {
                echo json_encode($Contact);
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
            if (!isset($data['Contact_number']) || !is_string($data['Contact_number'])) {
                http_response_code(400);
                echo json_encode(['message' => 'Invalid Contact_number']);
                return;
            }

            $ContactId = $this->model->createContact($data);
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
            if (!isset($data['Contact_number']) || !is_string($data['Contact_number'])) {
                http_response_code(400);
                echo json_encode(['message' => 'Invalid Contact_number']);
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