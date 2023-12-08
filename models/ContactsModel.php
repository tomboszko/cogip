<?php

class ContactModel {
    protected $db;

    public function __construct($database) {
        $this->db = $database;
    }


    public function getAllContacts() {
        $query = "SELECT contacts.*, companies.name AS company_name 
              FROM contacts 
              INNER JOIN companies ON contacts.company_id = companies.id";
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
        $query = "INSERT INTO Contacts (name, company_id, email, created_at, phone, updated_at) VALUES (:company_id, NOW(), NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $data['name'], PDO::PARAM_INT);
        $stmt->execute();
        return $this->db->lastInsertId();
    }    
    
    
    public function updateContact($id, $data) {
        $query = "UPDATE contacts SET name = :name, company_id = :company_id, email = :email, phone = :phone, updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
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
