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

    public function createContacts($data) {
        $query = "INSERT INTO Contacts (contact_name, company_id, email, created_at, phone, updated_at) VALUES (:company_id, NOW(), NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':contact_name', $data['contact_name'], PDO::PARAM_INT);
        $stmt->execute();
        return $this->db->lastInsertId();
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

    // Get all Contacts for a company
    public function getCompanyContacts($id) {

        $query = "SELECT id, contact_name, company_id, email, phone FROM Contacts WHERE company_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
