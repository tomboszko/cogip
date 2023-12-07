<?php

class CompanyModel {
    protected $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function getAllCompanies() {
        $query = "SELECT companies.*, types.name AS type_name 
          FROM companies 
          INNER JOIN types ON companies.type_id = types.id";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCompanyById($id) {
        $query = "SELECT companies.*, types.name AS type_name 
          FROM companies 
          INNER JOIN types ON companies.type_id = types.id 
          WHERE companies.id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createCompany($data) {
        $query = "INSERT INTO companies (company_name) VALUES (:company_name)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':company_name', $data['company_name']);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function updateCompany($id, $data) {
        $query = "UPDATE companies SET company_name = :company_name WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':company_name', $data['company_name']);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function deleteCompany($id) {
        $query = "DELETE FROM companies WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }

    // Get the last 2 companies
public function getLastCompanies() {
        $query = "SELECT * FROM companies ORDER BY id DESC LIMIT 2";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Get all invoices for a company
    public function getCompanyInvoices($id) {

        $query = "SELECT id, ref, created_at, updated_at FROM invoices WHERE id_company = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    
}


