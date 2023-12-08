<?php

class WelcomeModel {
    protected $db;

    public function __construct($database) {
        $this->db = $database;
    }
    // Get the last 3 companies
    public function getLastCompanies() {
        $query = "SELECT companies.*, types.name AS type_name
        FROM companies 
        INNER JOIN types ON companies.type_id = types.id";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get the last 3 Contacts
    public function getLastContacts() {
        $query = "SELECT contacts.*, companies.name AS company_name 
        FROM contacts 
        INNER JOIN companies ON contacts.company_id = companies.id";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Get the last 3 Invoices
    public function getLastInvoices() {
        $query = "SELECT invoices.*, companies.name AS company_name 
        FROM invoices 
        INNER JOIN companies ON invoices.company_id = companies.id";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}