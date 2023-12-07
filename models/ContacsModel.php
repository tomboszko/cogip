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

    public function getContactseById($id) {
        $query = "SELECT * FROM contacts WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createContact($data) {
        $query = "INSERT INTO invoices (contact_name, contact_id, email, phone, created_at, updated_at) VALUES (:contact_id, NOW(), NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':contact_id', $data['contact_id'], PDO::PARAM_INT);
        $stmt->execute();
        return $this->db->lastInsertId();
    }
    
    public function updateContact($id, $data) {
        $query = "UPDATE contacts SET ref = :contact_name, contact_id = :contact_id, updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':contact_id', $data['contact_id'], PDO::PARAM_INT);
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
