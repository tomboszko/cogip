<?php

class ContactModel {
    protected $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function getAllContacts() {
        $query = "SELECT * FROM contacts";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getContactById($id) {
        $query = "SELECT * FROM contacts WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createContact($data) {
        try {
            // Vérifie si 'contact_name' existe dans $data
            if (!isset($data['contact_name']) || !is_string($data['contact_name'])) {
                http_response_code(400);
                echo json_encode(['message' => 'Invalid contact_name']);
                return;
            }
    
            // Utilise ':contact_name' dans la requête SQL
            $query = "INSERT INTO contacts (contact_name, company_id, email, phone, created_at, updated_at) VALUES (:contact_name, :company_id, :email, :phone, NOW(), NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':contact_name', $data['contact_name'], PDO::PARAM_STR);
            $stmt->bindParam(':company_id', $data['company_id'], PDO::PARAM_INT);
            $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $stmt->bindParam(':phone', $data['phone'], PDO::PARAM_STR);
            $stmt->execute();
            $contactId = $this->db->lastInsertId();
    
            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode(['message' => 'Contact created', 'id' => $contactId]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'An error occurred while creating the Contact']);
        }
    }
    
    
    
    public function updateContact($id, $data) {
        $query = "UPDATE contacts SET contact_name = :contact_name, company_id = :company_id, email = :email, phone = :phone, updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':contact_name', $data['contact_name'], PDO::PARAM_STR);
        $stmt->bindParam(':company_id', $data['company_id'], PDO::PARAM_INT);
        $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
        $stmt->bindParam(':phone', $data['phone'], PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function deleteContact($id) {
        $query = "DELETE FROM contacts WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }
}
