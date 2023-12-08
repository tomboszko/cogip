<?php

class ShowModel {
    protected $db;

    public function __construct($database) {
        $this->db = $database;
    }
// Get all invoices for a company
    public function getCompanyInvoices($id) {
        $query = "SELECT invoices.*, companies.name AS company_name 
        FROM invoices 
        INNER JOIN companies ON invoices.id_company = companies.id
                WHERE invoices.id_company = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    // Get all Contacts for a company
    public function getCompanyContacts($id) {
            $query = "SELECT contacts.*, companies.name AS company_name 
                FROM contacts 
                INNER JOIN companies ON contacts.company_id = companies.id
                  WHERE contacts.company_id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

        // Get a company for show
    public function getShowCompanyById($id) {
        $query = "SELECT companies.*, types.name AS type_name
                    FROM companies 
                    INNER JOIN types ON companies.type_id = types.id
                    WHERE companies.id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}